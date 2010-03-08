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
?>
<form action="" onsubmit="return false;">

<p>
<input type="text" name="valeur" id="valeur"/>
</p>
<p>
<select name="order" id="order">
<option value="host"><?php echo _("Chercher par nom d'hote"); ?></option>
<option value="ip"><?php echo _("Chercher par IP"); ?></option>
<option value="referer"><?php echo _("Chercher par referer"); ?></option>
</select>
</p>
<p>
<input type="button" value="<?php echo _("Rechercher"); ?>" id="recherche" class="lien_recherche"/>
</p>
</form>