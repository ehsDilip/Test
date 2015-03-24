<?php

require_once 'library/HTMLPurifier.auto.php';

$dirty_html='List of Chief Ministers of AssamChief Minister of Assam
(Assam Mukhya Mantri</i>) </tr><td colspan="2"> <div>Seal of Assam</div> <tr> <b>Incumbent

since&nbsp;17 May 2001</small> Appointer</th> Inaugural holder Formation</th> 11 February 1946 </table><p>The of , a , is the of the . As per the , the is the state\'s head, but executive authority rests with the chief minister. Following elections to the , the governor usually invites the party (or coalition) with a majority of seats to form the government. The governor appoints the chief minister, whose are to the assembly. Given that he has the confidence of the assembly, the chief minister\'s term is for five years and is subject to no .</p>

Since 1946, Assam has had 14 chief ministers. The first was of the . Serving since 2001, Congressman is the longest-serving-chief minister.
<h2>[</span>] <th>No Name <th colspan="2">Term of office <th colspan="2">Party <th>Days in office</th> 1 <td>11 February 1946 6 August 1950 </td> 1,6382 9 August 1950 27 December 1957 <td>2,698</td> <td>28 December 1957 <td>6 November 1970</td> <tr>4</td> 30 January 1972 446 5 31 January 1972 <td>12 March 1978 2,232 6 12 March 1978 <td>4 September 1979 <td rowspan="2"> 542</td> 7 <td>9 September 1979 11 December 1979</td> </tr><td>&ndash; <td>Vacant<br> () 12 December 1979 5 December 1980 N/A</td> 8 6 December 198030 June 1981</td> 207</td> Vacant 30 June 1981</td> 13 January 1982 N/A <td>197 <tr>9</td> 19 March 1982 </td> 66 </tr><td>&ndash; Vacant<br> () 19 March 1982 <td>27 February 1983 N/A345 10 27 February 1983 23 December 1985 </td> 1,03111</td> 28 November 1990 <td>1,799</td> Vacant 28 November 1990 <td>30 June 1991 N/A <td>214 <tr>12 [2] 30 June 1991 22 April 1996 </td> [Total 2,788] 1,757 13 22 April 1996 14 May 1996 23 <tr> [2] 15 May 1996 17 May 2001 <td width="4px"> [Total 3,628] 1,829</td> 14 <td>17 May 2001 4,962 </table><dl>
Notes </dl>This column only names the chief minister\'s party. The state government he heads may be a complex coalition of several parties and independents; these are not listed here.</span>
^ </span> <span>When is in force in a state, its council of ministers stands dissolved. The office of chief minister thus lies vacant. At times, the legislative assembly also stands dissolved.</li> ';

$config = HTMLPurifier_Config::createDefault();
$purifier = new HTMLPurifier($config);
echo $clean_html = $purifier->purify($dirty_html);
