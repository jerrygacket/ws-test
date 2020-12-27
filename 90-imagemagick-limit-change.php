<?php
///etc/ImageMagick-6/policy.xml
exec("mv /etc/ImageMagick-6/policy.xml /etc/ImageMagick-6/policy-distr.xml", $output, $exit_code);
exec("cp policy.xml /etc/ImageMagick-6/policy.xml", $output, $exit_code);
