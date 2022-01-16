#!/bin/bash

BASEDIR=$(dirname $0)
echo "[** MYCDN cache garbage collector **]";

if [ ! -d ${BASEDIR}/www ]; then
  echo "[ERROR] There is no folder named \"www\"";
  exit
fi

echo "Removing files that have not been accessed for 5 days..."
/usr/bin/find ${BASEDIR}/www -mindepth 2 -atime +5 -type f \( -iname \*.png -o -iname \*.jpg -o -iname \*.jpeg -o -iname \*.gif -o -iname \*.css -o -iname \*.js \);
/usr/bin/find ${BASEDIR}/www -mindepth 2 -atime +5 -type f \( -iname \*.png -o -iname \*.jpg -o -iname \*.jpeg -o -iname \*.gif -o -iname \*.css -o -iname \*.js \) -exec rm -f {} \;

echo "Removing empty folder..."
/usr/bin/find ${BASEDIR}/www -type d -empty -print
/usr/bin/find ${BASEDIR}/www -type d -empty -delete

echo "Done."