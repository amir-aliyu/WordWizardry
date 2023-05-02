<html>

	<head>

		<title>WORD WIZARDRY</title>

<!-- setting up the site's style-->
<style>
    .wrapper{
	display: inline-block;
	border: 8px solid black;
	margin: 30px;
    }
</style>

	</head>

	<body style="background-color:#1d201f" > <div style="background-color:#996888">

<center><h2> Welcome to the Word Wizardry Website!<br> This is the premier destination for all your definition, synonym, and random word-finding needs!</h2></div>
	<center>

<h2  style = "background-color:#f7e7ce" width = 400> Find a Synonym or Definition</h2>

<!-- set up form to enter synonym, start/end letters -->		
<form method ="post" style = "background-color:#f7e7ce" class = 'wrapper' width = 400 action="project2.php"><p>

		<!-- setup first word-->
		I want a: 
		<select name= "synonym_def" id = "synonym_def">
			<option value="synonym">synonym</option>
			<option value="definition">definition</option>
		</select>		
		for: <input type="text" name="word1"><br>

		<!-- options for second word--> 
		<label for="start-end-rhyme">That </label>
		<select name="start-end-rhyme" id="start-end-rhyme">
			<option value="start">starts with</option>
			<option value="end">ends with</option>
		</select>
		<input type="text" name="word2">
		<input type = "submit" value = "go!" />

		<!-- setup form to enter sentence-->
		<br><br>Enter a sentence below to return a similar sentence!<br>
		<textarea rows="4" cols="30" name="sentence">Start typing...</textarea>
		<br>
		<input type="submit" value="submit sentence">

<center>
</div><div style="background-color:#D7DAB8" >

<?php 

// set up variables for inputs
$text = $_POST["userinput"];
$word1 = $_POST["word1"];
$word2= $_POST["word2"];
$start_end_rhyme= $_POST["start-end-rhyme"];
$synonym_def = $_POST["synonym_def"];
$sentence = $_POST["sentence"];

// set up booleans for synonyms/definitions found
$hasSynonym = false;
$hasDefinition = false;

function hasSynonym($word1,$synonyms_arr) {

	 $synonyms_arr = explode("\n", file_get_contents('synonyms.csv'));

        // if the inputted word has been found in the file, search for its synonym
        return in_array($word1, $synonyms_arr); 

}

function hasDefinition($word1, $dictionary_arr) {

	$dictionary_arr = explode("\n", file_get_contents('dictionary.csv'));
	$word1 = ucfirst($word1);
	
	// if word has a definition
        return in_array($word1, $dictionary_arr); 


}


// function taking in a word, returning its synonyms
function findSynonym($word1, $word2, $start_end_rhyme) {

	$synonyms_arr = explode("\n", file_get_contents('synonyms.csv'));

	$start_end_rhyme = $_POST["start-end-rhyme"];

	if (hasSynonym($word1, $synonyms_arr)) {

			$hasSynonym = true;
			$i = array_search($word1, $synonyms_arr);
                        // the synonym file is organized with the synonyms of each word one line beneath the given word, so the synonyms are always one over from the word
                        $found_word = $synonyms_arr[$i+1];
                        // create an array to store all the resulting synonyms from user input
                        $found_words_arr = explode(", ", $found_word);

                        // if no starting/end words specified, print all the synonyms
                        if (strlen($word2)=== 0) {
                                echo "<h3>Here are all the total synonyms for " . $word1 . "</h3>";
                                echo $found_word;

                        } else {
                                // create an array to put all the words that start with/end with what user wants
                                $start_end_matches = array();

                                if ($start_end_rhyme == "start") {
                                        // iterate through all found synonyms
                                        for($i = 0; $i < count($found_words_arr); $i++) {

                                                // compare all found synonyms with input user specified to start with, starting from the beginning
                                                if (substr_compare($found_words_arr[$i], $word2, 0, strlen($word2)) === 0) {

                                                        $was_found = true;
                                                        $start_end_matches[$i] = $found_words_arr[$i];
                                                }
                                        }
                                }

                                if ($start_end_rhyme == "end") {

                                        for($i = 0; $i < count($found_words_arr); $i++) {

                                                // compare all found synonyms with input user specified to end with, since last parameter is negative, starts from the end
                                                if (substr_compare($found_words_arr[$i], $word2, -strlen($word2)) === 0) {

                                                        $was_found = true;
                                                        $start_end_matches[$i] = $found_words_arr[$i];
                                                }
                                        }

                                }
                                // if a match for start/end word found and synonyms found, display them
                                if ($was_found==true) {

                                        echo "<h3>Here are all the synonyms for " . $word1 . " that " . $start_end_rhyme . " with " . $word2 . "</h3>";
                                        foreach ($start_end_matches as $value) {
                                                echo "$value ";
                                        }
                                        echo "<h3>Here are all the total synonyms for " . $word1 . "</h3>";
                                        echo $found_word;
                                        // if no matches for start/end word found, display all found synonyms
                                } else {

                                        echo "<h3>There are no synonyms for " . $word1 . " that " . $start_end_rhyme . " with " . $word2 .  ", here are all the total synonyms:</h3><br> ";
                                        echo $found_word;

                                }
      
                        }
                        // if no synonyms at all found, ask for different word
                } else {

                        echo "<h3>No synonyms found";

                }
}

// function to find a definition based on a word 

function defineWord($word1) {
// create an array with all dictionary contents
                $dictionary_arr = explode("\n", file_get_contents('dictionary.csv'));

		$word1 = ucfirst($word1);
                // if word has a definition
                if (in_array($word1, $dictionary_arr)) {

			$hasDefinition = true;
                        $i = array_search($word1, $dictionary_arr);

                        // the dictionary file is organized with the definitions of each word one line beneath the given word, so the definitions are always one over from the word
                        $found_word = $dictionary_arr[$i+2];
                        $searchword = $word1;
                        $matches = array();

                        // since there are multiple definitions for each word in the array, find every instance of the word and return its definition
                        for($i = 0; $i < 163674; $i++) {

                                if (preg_match("/$searchword/", $dictionary_arr[$i])) {
                                        $matches[$i] = $dictionary_arr[$i+2];
				}
                        }

                        // print out all the definitions
                        echo "<h3>Here are all the definitions for " . $word1 . "</h3>";

                        foreach($matches as $v) {
                                if($v != "") {
                                        echo "~" . $v . "<br>";
                                }
                        }

                } else {
			echo "<h3>There are no definitions for " . $word1;  
                }


}

function synonymSentence($sentence) {
	// create an array with all the words in the sentence
	$sentence_arr = explode(" ", $sentence);	
	$synonyms_arr = explode("\n", file_get_contents('synonyms.csv'));

	// for every word in the sentence
	for($x = 0; $x < count($sentence_arr); $x++) {

		// if there is a synonym for the word
		if (in_array($sentence_arr[$x], $synonyms_arr)) {

			// find the synonyms and create an array to store them	
			$i = array_search($sentence_arr[$x], $synonyms_arr);
			$found_word = $synonyms_arr[$i+1];
			$found_words_arr = explode(", ", $found_word);

			// filter out empty elements
			array_filter($found_words_arr);

			// choose a random synonym from the array of synonyms
			$random_num = rand(0, count($found_words_arr));

			//if the array isn't empty and the word is greater than 3 characters, replace with a random synonym
			if (count($found_words_arr) > 0 && strlen($sentence_arr[$x]) > 3){
				$sentence_arr[$x] = substr_replace($sentence_arr[$x], $found_words_arr[$random_num], $sentence);

			}
		}
			echo $sentence_arr[$x] . " ";

	}

}

if ($_POST) {

	$start_end_rhyme = $_POST["start-end-rhyme"];

	// if they wrote in the sentence box, find synonyms for every word in sentence and return
	if ($sentence != "Start typing...") {

		echo "Your similar sentence is: <br>";		
		synonymSentence($sentence);	

		echo "<br><br>You entered: " . "<br>" .  $sentence . "<br>";
	}

	// if chose synonym option, find the synonym
	if ($synonym_def == "synonym") {
		if ($word1 != "") {	
			findSynonym($word1,$word2, $start_end_rhyme);

			// if there is no synonym but there is a definition, define the word
			if (hasSynonym($word1, $synonyms_arr) == false && hasDefinition($word1, $dictionary_arr)) {
			
				defineWord($word1);
			}
	       }
	}

	// if chose to define word, find the definition
	else {
		defineWord($word1);

		// if there is no definition but there is a synonym, return the word's synonym
		if (hasDefinition($word1, $dictionary_arr)  == false && hasSynonym($word1, $synonyms_arr)) {
			echo findSynonym($word1,$word2,$start_end_rhyme);
		}
	}
}


echo "<center></div><div style = 'background-color:#CAF4F4' class = 'wrapper'>"; // formatting
// set up random word from a file with only words that have synonyms
$random_words = explode("\n", file_get_contents('words1.csv'));
// store random number
$random_num = rand(3, count($random_words));

?>

<h2><p id = "random" >Click me to generate a random word!</p></h2>
<script>

document.getElementById("random").onclick = function() {getRandWord()};

// get a random word based on random number 
function getRandWord() {
	// display random word to screen
	var rand="<?php echo "The random word is: " . $random_words[$random_num]; ?>";
	alert(rand);
	// change what the box says based on click
	document.getElementById("random").innerHTML = "<?php echo "<h4>Try to find your random word 's synonym!<br></h4><h5>Remember, your word was " . $random_words[$random_num]; ?>";   
}



</script>


