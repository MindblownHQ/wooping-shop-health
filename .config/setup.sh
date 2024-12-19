#!/bin/sh
if [ ! -L .git/hooks ]; then
    echo ".git/hooks is not a symlink"
    echo "Moving .git/hooks to .git/hooks.bak"
    mv .git/hooks .git/hooks.bak

    echo "Creating symlink from .config/hooks to .git/hooks" 
    ln -s ../.config/hooks .git/hooks
else
    echo ".git/hooks is already a symlink"
fi