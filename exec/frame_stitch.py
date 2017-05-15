#!/usr/bin/env python

#command format: ./frame_stitch [name of video]
#---->  example: ./frame_split test

import subprocess
import sys
import extract_metadata

vidid = sys.argv[1]
path_to_vid = "videos/" + vidid + ".mp4"
input_folder = "frames"
output_folder = "output_video"
out_vid = vidid + ".output"
fps = 15#extract_metadata.find_fps(path_to_vid)


def stitch_stills(folder_in=input_folder, folder_out=output_folder, name=out_vid):
    cmd_patch = ["ffmpeg", "-r", str(fps), "-start_number", "1", "-f", "image2", "-i",
                 folder_in + "/" + vidid + ".%d.tri.png", "-pix_fmt", "yuv420p",
                 "-vcodec", "libx264", "-vf", "scale=1280:-2", folder_out + "/" + name + ".mp4"]
    subprocess.call(cmd_patch)

if __name__ == '__main__':
    stitch_stills()
