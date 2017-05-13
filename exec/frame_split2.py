#!/usr/bin/env python

#command format: ./frame_split [name of video to be split]
#---->  example: ./frame_split test

import subprocess
import sys
import os

vidid = sys.argv[1]
path_to_vid = "videos/" + vidid + ".mp4"


def extract_stills():
    cmd_split = ["ffmpeg", "-i", path_to_vid, "-f", "image2", "frames/" + vidid + ".%d.png"]
    subprocess.call(cmd_split)

if __name__ == '__main__':
    print extract_stills()

