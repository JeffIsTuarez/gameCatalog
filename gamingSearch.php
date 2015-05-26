<?php
include('header.php');
?>

<h1 class="pageTitle">Search</h1>
  
  <h3 >Check what you want to search for (No results means nothing matches your query)</h3>
  <form action="gamingSearch.php" method="post">
    <input type="checkbox" name="namecheckbox">Name of the Game: <input type="text" name="name" /></input><br />
	<input type="checkbox" name="publishercheckbox">Publisher: <input type="text" name="publisher" /></input><br />
	<input type="checkbox" name="yearcheckbox">Year: <input type="number" min="1970" max="2014" name="year" /> </input><br />
	<input type="checkbox" name="playercheckbox">Single Player or Multi Player: 
		<input type="radio" name="player" value="Single-Player" /> Single Player
		<input type="radio" name="player" value="Multi-Player" /> Multi-Player </input> <br />
	<input type="checkbox" name="genrecheckbox">Genre: <select name="genre">
				<option value="Action"> Action </option>
				<option value="Adventure"> Adventure  </option>
				<option value="Fighting"> Fighting </option>
				<option value="RPG"> Role-Playing </option>
				<option value="MOBA"> MOBA </option>
				<option value="Platformer"> Platformer </option>
				<option value="Simulation"> Simulation </option>
				<option value="Strategy"> Strategy </option>						
				<option value="Sports"> Sports </option>
				<option value="Shooter"> Shooter </option>
				<option value="Other"> Other </option>
			</select> </input><br />
	<input type="checkbox" name="ratingcheckbox">ESRB Rating:<select name="rating">
					<option value="E"> E (Everyone) </option>
					<option value="T"> T (Teen) </option>
					<option value="M"> M (Mature) </option>
				</select></input> <br />
    <input type="submit" name="submit" value="Search" />
  </form>
 
<h3 class="listTitle">List of Games</h3>
  
  <table>
	<tr>
		<th class="rowtitle"> Title </th>
		<th class="rowtitle"> Publisher </th>
		<th class="rowtitle"> Year </th>
		<th class="rowtitle"> Single or Multi-Player </th>
		<th class="rowtitle"> Genre </th>
		<th class="rowtitle"> ESRB Rating </th>
		<th class="rowtitle"> Description </th>
	</tr>
<?php
	if(isset($_POST['submit']))
{
		$delimiter = '-**-';
		$error=false;
		$searchname="";
		$searchpublisher="";
		$searchyear="";
		$searchplayer="";
		$searchgenre="";
		$searchrating="";
		
		
		if(!isset($_POST['namecheckbox']) && !isset($_POST['publishercheckbox']) && !isset($_POST['yearcheckbox']) && !isset($_POST['playercheckbox'])
		&& !isset($_POST['genrecheckbox']) && !isset($_POST['ratingcheckbox']))
		{
			print "<p class='error'>You didn't check anything, check something to search for please.</p>";
			$error=true;
		}
		if(isset($_POST['namecheckbox']))
		{
			$searchname = trim(strip_tags($_POST['name']));
			if(!isset($searchname) || empty($searchname))
			{
				print "<p class='error'>The Name field is not filled out, please fill it out.</p>";
				$error=true;
			}
			else
			{
				$searchname = trim(strip_tags($_POST['name']));
			}
		}
		if(isset($_POST['publishercheckbox']))
		{
			$searchpublisher = trim(strip_tags($_POST['publisher']));
			if(!isset($searchpublisher) || empty($searchpublisher))
			{
				print "<p class='error'>The Publisher field is not filled out, please fill it out </p>";
				$error=true;
			}
		}
		if(isset($_POST['yearcheckbox']))
		{
			$searchyear= strip_tags($_POST['year']);
			if(!is_numeric($searchyear) || ($searchyear<1970))
			{
				print "<p class='error'> Year must be valued at 1970 or later.</p>";
				$error=true;
			}
		}
		if(isset($_POST['playercheckbox']))
		{
			if(!isset($_POST['player']))
			{
				print "<p class='error'> Is it Single Player or Multi Player? Pick one. </p>";
				$error=true;
			}
			else
			{
				$searchplayer= ($_POST['player']);
			}
		}
		if(isset($_POST['genrecheckbox']))
		{
			$searchgenre= ($_POST['genre']);
		}
		if(isset($_POST['ratingcheckbox']))
		{
			$searchrating= ($_POST['rating']);
		}
		$delimiterchecker= strstr($searchname, $delimiter)!=null || strstr($searchpublisher, $delimiter)!=null;
		if($delimiterchecker)
		{
			print "<p class='error'> Shoot, you found my delimiter... Pls don't do that </p>";
			$error=true;
		}
		if($error==true)
		{
			print  "<p class='error'> Will not search unless all the errors are fixed </p>";
		}
		else
		{
			$searcharray=array($searchname,$searchpublisher,$searchyear,$searchplayer,$searchgenre,$searchrating);
			
		if(file_exists("games.txt"))
		{
			$games=file("games.txt");			
			foreach($games as $game)
			{
				$patternname="/\b".$searcharray[0]."\b/i";
				$patternpublisher="/\b".$searcharray[1]."\b/i";
				$patternyear="/\b".$searcharray[2]."\b/i";
				$patternplayer="/\b".$searcharray[3]."\b/i";
				$patterngenre="/\b".$searcharray[4]."\b/i";
				$patternrating="/\b".$searcharray[5]."\b/i";
				$info= explode($delimiter,$game);
				if((preg_match($patternname, $info[0])||$patternname=="") && (preg_match($patternpublisher, $info[1])||$patternpublisher=="")
				&& (preg_match($patternyear, $info[2])||$patternyear=="") && (preg_match($patternplayer, $info[3]) ||$patternplayer=="")
				&& (preg_match($patterngenre,$info[4])||$patterngenre=="") && (preg_match($patternrating, $info[5]) ||$patternrating==""))
				{
					print "<tr>
					<td class='title'>$info[0]</td>
					<td>$info[1]</td>
					<td>$info[2]</td>
					<td>$info[3]</td>
					<td>$info[4]</td>
					<td>$info[5]</td>
					<td class='title'>$info[6]</td>
					</tr>";
				}
			}
		}
		else
		{
			print "</table><p class='error'>The game database hasn't been built yet</p></table>";
		}
	}
		
}
?>
</table>