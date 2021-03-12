#!/usr/bin/env bash

aws s3 cp s3://exposuresoftware-configs/singlemomfinder.conf /etc/nginx/sites-enabled/singlemomfinder.conf
service nginx restart
