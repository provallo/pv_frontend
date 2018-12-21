#!/bin/bash

BASE_PATH=$(dirname $PWD/$0)
SOURCE=$BASE_PATH/_resources/less/all.less
TARGET=$BASE_PATH/_resources/css/all.css

echo "Creating directory ..."
mkdir -p $(dirname TARGET)

echo "Compiling ..."
lessc $SOURCE $TARGET