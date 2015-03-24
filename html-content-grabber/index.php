<?php
/***************************************************************
 *  Copyright (C) 2012  Nabil Droussi <nabildroussi@gmail.com>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 ***************************************************************/
 
require_once 'HtmlContentGrabber.php';

$html = new HtmlContentGrabber();
//$html->connect("https://www.google.co.in", "/", 80);

//$html->connect("https://www.google.co.in", "/annuaire/paris-75/plombiers?portail=PJ", 80);

var_dump($html->getCookies());
?>