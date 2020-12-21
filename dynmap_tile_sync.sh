#!/bin/bash -e

# TODO:
# Write a lock file to prevent the next script from running if this one over-runs

user=
id_file=
host=
port=22
server_hash=
daemon_data_dir=/srv/daemon-data
tmp_dir=/srv/dynmap_tile_sync
destination_dir=

usage() {
  echo "$0 -u [user] -i [id_file] -h [remove_server] -s [server_hash] -df [destination_dir] [ -p [port] | -dd [daemon_data_directory] | -dt [temporary_directory] ]"
}

# Parse argumnets
while [ ! -z "$1" ]; do
  case $1 in -s | --server )
    shift
    server_hash=$1
  ;; -u | --user )
    shift
    user=$1
  ;; -i | --id )
    shift
    id_file=$1
  ;; -h | --host )
    shift
    host=$1
  ;; -p | --port )
    shift
    port=$1
  ;; -dd | --daemon-data-dir )
    shift
    daemon_data_dir=$1
  ;; -dt | --temporary-dir )
    shift
    tmp_dir=$1
  ;; -df | --destination-dir )
    shift
    destination_dir=$1
  ;; *)
    echo "Unknown argument $1."
    usage
    exit
  ;;
  esac
  shift
done

if [ -z "$server_hash" ]; then
  echo "No server hash provided"
  exit 3
fi

if [ -z "$user" ]; then
  echo "No target host login user provided"
  exit 3
fi

if [ -z "$id_file" ]; then
  echo "No target host login user identity file provided"
  exit 3
fi

if [ -z "$host" ]; then
  echo "No target host provided"
  exit 3
fi

if [ -z "$destination_dir" ]; then
  echo "No destination directory on remote server specified"
  exit 3
fi

# Check for lock file from previous execution
if test -f "$tmp_dir/$server_hash.lock"; then
  echo "Previous execution still has resource lock. Deferring execution."
  exit 3
fi

# Indicate resource lock
touch "$tmp_dir/$server_hash.lock"

export server_hash
export daemon_data_dir
export tmp_dir
cd $daemon_data_dir/$server_hash/plugins/dynmap/web
echo "Locating & packaging files for transfer"

find tiles/ -type f -name '*.jpg' | head -n 50000 | xargs tar --remove-files -rf $tmp_dir/$server_hash.tar

echo "Transfering files to CDN host"
scp -P $port -i $id_file $tmp_dir/$server_hash.tar $user@$host:$destination_dir
ssh -p $port -i $id_file $user@$host "tar -xf $destination_dir/$server_hash.tar && rm $destination_dir/$server_hash.tar"

# Cleanup files & resource lock
echo "Cleaning temp dir & releasing working lock"
#rm -rf $tmp_dir/$server_hash
rm $tmp_dir/$server_hash.tar
rm $tmp_dir/$server_hash.lock
