## Deepzoom Image creation with shell script
- This is a shell script that produce dzi from a file
- Sample
  - $result = shell_exec('./slicer-shell/magick-slicer.sh /input-image.jpg  -o /output/dzi -w 256 -v2');
- Some notes
  - The output ( -o option ) will create a folder named {output}-files and {output}.dzi in current folder
  - So to create the output into seperate folder, we should use {real}/{path}/{output_name}, it will create the files name output_name.dzi inside the {path} folder
  - Note that the folder should be created prior running the shell
- This method works well but it is too slow, can use libvips to achieve the same result with faster response