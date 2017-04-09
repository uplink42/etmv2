FusionCharts PHP Export Handler
==================================

What is FusionCharts PHP export handler?

FusionCharts Suite XT uses JavaScript to generate charts in the browser, using SVG and VML (for older IE). If you need
to export the charts as images or PDF, you need a server-side helper library to convert the SVG to image/PDF. These
export handlers allow you to take the SVG from FusionCharts charts and convert to image/PDF.

How does the export handler work?

- A chart is generated in the browser. When the export to image or PDF button is clicked, the chart generates the SVG
string to represent the current state and sends to the export handler. The export handler URL is configured via chart
attributes.
- The export handler accepts the SVG string along with chart configuration like chart type, width, height etc., and uses
InkScape & ImageMagick library to convert to image or PDF.
- The export handler either writes the image or PDF to disk, based on the configuration provided by chart, or streams it
back to the browser.

Version
=======

1.1

Requirements
============

Inkscape:

    Inkscape is an open source vector graphics editor. What sets Inkscape apart is its use of Scalable Vector Graphics
    (SVG), an open XML-based W3C standard, as the native format. Inkscape has a powerful command line interface and can
    be used in scripts for a variety of tasks, such as exporting and format conversions. For details, refer to the
    following page.

http://inkscape.org/doc/inkscape-man.html


ImageMagick:

    ImageMagick is a free and open-source software suite for displaying, converting, and editing raster image and vector
    image files. The software mainly consists of a number of command-line interface utilities for manipulating images.
    For further details, please refer to the the following page.

http://www.imagemagick.org/

Installation
============

*  You should have a Linux based server with Administrative facility to install softwares. This is particularly
important, if you are using a shared hosting service.
*  Both Inkscape and ImageMagick need to be installed in order to make the whole system work. Please visit to
the respective sites and follow the instructions on installation.
*  The folder named `tmp` and `ExportedImages` should be placed relative to the index.php. Both of these 2
folders should have write access, in order to write temporary and saved image files.
*  In chart data set the correct url to send the exported SVG string to the index.php.


License
-------

FUSIONCHARTS:

Copyright (c) FusionCharts Technologies LLP
License Information at http://www.fusioncharts.com/license

Known Issues / limitations:
---------------------------

*  When we export to an SVG file. The file renders correctly in browsers, but may not render properly in other image
 softwares.
*  If the chart has any external images as in logo, background or in anchors they will not get exported in the exported
image.
*  The whole system is configured for Linux based server.
