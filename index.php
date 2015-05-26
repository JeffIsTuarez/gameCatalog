<?php
require('header.php');
?>
  <h1 class="pageTitle">Gaming Catalog</h1>
  
  <h3 class="description">Input your own game</h3>
  <form action="index.php" method="post">
    Name of the Game: <input type="text" name="name" /> <br />
	Publisher: <input type="text" name="publisher" /> <br />
	Year: <input type="number" min="1970" max="2014" name="year" /> <br />
	Single Player or Multi Player: <input type="radio" name="player" value="Single-Player" /> Single Player
	<input type="radio" name="player" value="Multi-Player" /> Multi-Player<br />
	Genre: <select name="genre">
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
			</select> <br />
	ESRB Rating:<select name="rating">
					<option value="E"> E (Everyone) </option>
					<option value="T"> T (Teen) </option>
					<option value="M"> M (Mature) </option>
				</select> <br />					
	Description (Optional) (60 Characters or less) : <br /> <textarea name="description" maxlength="60" ></textarea><br />
    <input type="submit" name="submit" value="Save" />
  </form>
  <?php
  $delimiter = '-**-'; //set this in one place, don't hardcode it everywhere!
 
	if(isset($_POST['submit']))
	{		
		$name = trim(strip_tags($_POST['name']));
		$publisher = trim(strip_tags($_POST['publisher']));
		$delimiterchecker= strstr($name, $delimiter)!=null || strstr($publisher, $delimiter)!=null || strstr(trim(strip_tags($_POST['description'])), $delimiter)!=null;
		
		// Types of Error messages that can pop up
		if(!isset($name) || empty($name))
		{
			print "<p class='error'>The Name field is not filled out, please fill it out.</p>";
		}
		if(!isset($publisher) || empty($publisher))
		{
			print "<p class='error'>The Publisher field is not filled out, please fill it out </p>";
		}		
		if(!isset($_POST['player']))
		{
			print "<p class='error'> Is it Single Player or Multi Player? Pick one. </p>";
		}
		if($delimiterchecker)
		{
			print "<p class='error'> Shoot, you found my delimiter... Pls don't do that </p>";
		}
		if(!is_numeric(strip_tags($_POST['year'])) || (strip_tags($_POST['year'])<1970))
		{
			print "<p class='error'> Year must be valued at 1970 or later.</p>";
		}
		if(strlen(trim(strip_tags($_POST['description'])))>60)
		{
			print  "<p class='error'> Too long of a description.</p>";
		}
		//If it passes with no problem then it'll do its magic.
		if(isset($name) && !empty($name) && isset($publisher) && !empty($publisher) && isset($_POST['player']) && !$delimiterchecker &&(strip_tags($_POST['year'])>=1970) && 
		strlen(trim(strip_tags($_POST['description'])))<=60)
		{
			$player= $_POST['player'];
			$year= strip_tags($_POST['year']);
			$genre= $_POST['genre'];
			$rating= $_POST['rating'];
			$description= trim(strip_tags($_POST['description']));
			$file = fopen("games.txt", "a+");
		 
			//If it doesn't exist, it'll make one.
			if (!$file) 
			{
			  die("There was a problem opening the games.txt file");
			}
			$filecheck= file("games.txt");
			$notduplicate=true;
			//Checks if there's any duplicates
			foreach($filecheck as $line)
			{
				$info= explode($delimiter,$line);
				$true= $info[0]==$name&&$info[1]==$publisher&&$info[2]==$year&&$info[3]==$player&&$info[4]==$genre&&$info[5]==$rating;
				if($true==1)
				{
					$notduplicate=false;
				}
			}
			
			
			
			//If it is not a duplicate, then it puts it in the file
			if($notduplicate)
			{
				fputs($file, "$name$delimiter$publisher$delimiter$year$delimiter$player$delimiter$genre$delimiter$rating$delimiter$description\n");
			}
			else
			{
				print "<p class='error'>Duplicate detected, no duplicates please</p>";
			}
			//5. close the text file
			fclose($file);
		}
	}	
  
?>
  
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
		if(file_exists("games.txt"))
		{
			
			//6. get the contents of the text file as an array
			$games=file("games.txt");
			
			//7. Use for each to loop through each guest
			foreach($games as $game)
			{
				$info= explode($delimiter,$game);
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
		else
		{
			print "</table>
					<p class='error'> Nothing...Yet... </p>
					<table>";
		}
	  ?>
  </table>
</body>
 
</html>