#!/usr/bin/env python

#command format: ./frame_stitch [folder with triangle frames] [folder to output new video]
#---->  example: ./frame_split frames output_video

import subprocess
import sys
import extract_metadata

path_to_vid = "videos/test2.avi"
input_folder = sys.argv[1]
output_folder = sys.argv[2]
vid_id = "output2"
fps = extract_metadata.find_fps(path_to_vid)


def stitch_stills(folder_in=input_folder, folder_out=output_folder, name=vid_id):
    cmd_patch = ["ffmpeg", "-r", str(fps), "-start_number", "1", "-f", "image2", "-i",
                 folder_in + "/test2.%d.tri.png", "-pix_fmt", "yuv420p",
                 "-vcodec", "libx264", "-vf", "scale=1280:-2", folder_out + "/" + name + ".mp4"]
    subprocess.call(cmd_patch)

if __name__ == '__main__':
    stitch_stills()
