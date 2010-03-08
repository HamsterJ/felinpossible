<?php
# ***** BEGIN LICENSE BLOCK *****
# This file is part of SpongeStats
# Copyright (c) Bastien Bobe / Samy Rabih. All rights reserved.
#
# SpongeStats is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# SpongeStats is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# ***** END LICENSE BLOCK *****

$update = @fopen("http://spongestats.sourceforge.net/version.txt","r");
if (!$update) {
    echo "<img src=\"updates/information.png\" alt=\""._("Impossible de verifier les mises a jour")."\" title=\""._("Impossible de verifier les mises a jour")."\" style=\"vertical-align:middle;\" />";
  }
 else
 	{
	$update_date = @fgets($update, 4096);
	$update_local = @fopen("updates/version.txt","r");
	$old_date = @fgets($update_local, 4096);
	if($old_date == $update_date)
		{
		echo "<img src=\"updates/ok.png\" alt=\""._("Votre version est a jour")."\" title=\""._("Votre version est a jour")."\" style=\"vertical-align:middle;\"/>";
		}
	else
		{
		echo "<div id=\"notification\">"._("Une nouvelle version est disponible")."<a href=\"http://spongestats.sourceforge.net/download/\">"._("Cliquez ici pour telecharger la nouvelle version")."</a></div>";
		echo "<a href=\"http://spongestats.sourceforge.net/\"><img src=\"updates/new.png\" alt=\""._("Une nouvelle version est disponible")."\" title=\""._("Une nouvelle version est disponible")."\" style=\"border:0px;vertical-align:middle;\"/></a>";
		}
	}
?>
