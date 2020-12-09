#!/bin/bash -e

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

export server_hash
export daemon_data_dir
export tmp_dir
cd $daemon_data_dir/$server_hash/plugins/dynmap/web
find * -name '*.jpg' -exec sh -c '
  dir=$(dirname $0)
  mkdir -p $tmp_dir/$server_hash/$dir
  mv $daemon_data_dir/$server_hash/plugins/dynmap/web/$0 $tmp_dir/$server_hash/$dir
' {} \;

scp -P $port -i $id_file -r $tmp_dir/$server_hash/* $user@$host:$destination_dir
rm -rf $tmp_dir/$server_hash
