#!/usr/bin/env bash

xs_path="/usr/local/xunsearch"

rm -f ${xs_path}/tmp/pid.*

echo -n > ${xs_path}/tmp/docker.log

${xs_path}/bin/xs-indexd -l ${xs_path}/tmp/docker.log -k start

sleep 1

${xs_path}/bin/xs-searchd -l ${xs_path}/tmp/docker.log -k start

sleep 1

tail -f ${xs_path}/tmp/docker.log