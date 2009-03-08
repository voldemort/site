#!/bin/bash

rsync -r --progress --cvs-exclude --archive --delete --exclude .svn www/* empathybox@empathybox.com:/home/empathybox/project-voldemort.com/