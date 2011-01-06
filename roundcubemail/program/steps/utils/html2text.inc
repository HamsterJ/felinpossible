<?php
/*

 +-----------------------------------------------------------------------+
 | program/steps/utils/html2text.inc                                     |
 |                                                                       |
 | This file is part of the Roundcube Webmail client                     |
 | Copyright (C) 2005-2010, Roundcube Dev. - Switzerland                 |
 | Licensed under the GNU GPL                                            |
 |                                                                       |
 | PURPOSE:                                                              |
 |   Convert HTML message to plain text                                  |
 |                                                                       |
 +-----------------------------------------------------------------------+
 | Author: Thomas Bruederli <roundcube@gmail.com>                        |
 +-----------------------------------------------------------------------+

 $Id: html2text.inc 4349 2010-12-17 15:07:04Z thomasb $

*/

$html = $HTTP_RAW_POST_DATA;

// Replace emoticon images with its text representation
$html = rcmail_replace_emoticons($html);

$converter = new html2text($html);

header('Content-Type: text/plain; charset=UTF-8');
print rtrim($converter->get_text());
exit;


