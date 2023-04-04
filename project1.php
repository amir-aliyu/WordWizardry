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

<center><h2> Welcome to the Word Wizardry Website!<br> This is the premier destination for all your word of the day and synonym-finding needs!</h2></div>
        <center>

<h2  style = "background-color:#f7e7ce" width = 400> Find a Synonym!</h2>

<!-- set up form to enter synonym, start/end letters -->
<form method ="post" style = "background-color:#f7e7ce" class = 'wrapper' width = 400 action="project1.php"><p>

                <!-- setup first word-->
                I want a synonym for: <input type="text" name="word1"><br>

                <!-- options for second word-->
                <label for="start-end-rhyme">That </label>
                <select name="start-end-rhyme" id="start-end-rhyme">
                        <option value="start">starts with</option>
                        <option value="end">ends with</option>
                </select>
                <input type="text" name="word2">
                <input type = "submit" value = "go!" />
<center>
</div><div style="background-color:#9ED9CCFF" class = 'wrapper'>

<?php
 if ($_POST) {

         // set up variables for header
         $text = $_POST["userinput"];
         $word1 = $_POST["word1"];
         $word2= $_POST["word2"];
         $start_end_rhyme= $_POST["start-end-rhyme"];

         // create an array to store all the synonyms for word1 
         $synonyms_arr = explode("\n", file_get_contents('synonyms.csv'));

         echo "<center>";
         // if the inputted word has been found in the file, search for its synonym
         if(in_array($word1, $synonyms_arr)) {
                 $i = array_search($word1, $synonyms_arr);
                 // the synonym file is organized with the synonyms of each word one line beneath the given word, so the synonyms are always one over from the word
                 $found_word = $synonyms_arr[$i+1];
                 // create an array to store all the resulting synonyms from user input 
                 $found_words_arr = explode(", ", $found_word);

                // if no starting/end words specified, print all the synonyms 
                 if(strlen($word2)=== 0) {
                         echo "<h3>Here are all the total synonyms for " . $word1 . "</h3>";
                         echo $found_word;

                 } else {
                         // create an array to put all the words that start with/end with what user wants       
                         $start_end_matches = array();

                         if($start_end_rhyme == "start") {
                                 // iterate through all found synonyms
                                 for($i = 0; $i < count($found_words_arr); $i++) {

                                         // compare all found synonyms with input user specified to start with, starting from the beginning  
                                         if (substr_compare($found_words_arr[$i], $word2, 0, strlen($word2)) === 0) {

                                                 $was_found = true;
                                                 $start_end_matches[$i] = $found_words_arr[$i];
                                         }
                                 }
                         }

                         if($start_end_rhyme == "end") {

                                 for($i = 0; $i < count($found_words_arr); $i++) {

                                         // compare all found synonyms with input user specified to end with, since last parameter is negative, starts from the end
                                        if (substr_compare($found_words_arr[$i], $word2, -strlen($word2)) === 0) {

                                                $was_found = true;
                                                $start_end_matches[$i] = $found_words_arr[$i];
                                        }
                                 }

                        }
                         // if a match for start/end word found and synonyms found, display them
                         if($was_found==true) {    
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
         }else {

                echo "<h3>No synonyms found, please try a different word.</h3>";

         }
        echo "<center></div><div style = 'background-color:#588157' class = 'wrapper'>"; // formatting
         // set up random word from a file with only words that have synonyms 
         $random_words = explode("\n", file_get_contents('words1.csv'));
         // store random number 
         $random_num = rand(3, count($random_words));


 }

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


        </form>

