<?php

class EzeValHtmlReporter extends HTMLReporter {
    function paintPass($message) {
        parent::paintPass($message);
        $breadcrumb = $this->getTestList();
        print "<font color='green'>Pass: </font>".$breadcrumb[1]."<br/>";
    }



    function paintFooter($test_name) {
        $colour = ($this->getFailCount() + $this->getExceptionCount() > 0 ? "red" : "green");

        print "<table border=0 width=100%>";
        print "<tr><td bgcolor='lightgray' border='1'>";
        print "<font color='green'> <b>".$this->getPassCount()."</b> passes</font>, ";
        print "<font color='red'> <b>".$this->getFailCount()."</b> fails</font>, and ";
        print "<font color='red'> <b>".$this->getExceptionCount()."</b> unexpected exceptions.</font>";
        print "</table>";
//        print "</td></tr></table>";
/*
        print "<div style=\"";
        print "padding: 8px; margin-top: 1em; background-color: $colour; color: white;";
        print "\">";
        print "<strong>" . $this->getPassCount() . "</strong> passes, ";
        print "<strong>" . $this->getFailCount() . "</strong> fails and ";
        print "<strong>" . $this->getExceptionCount() . "</strong> exceptions.";
        print "</div>\n";
*/
    }

    /**
     *    Paints the top of the web page setting the
     *    title to the name of the starting test.
     *    @param string $test_name      Name class of test.
     *    @access public
     */
    function paintHeader($test_name) {
        $this->sendNoCacheHeaders();
        print "<br/><br/>";
        print "<h1>$test_name</h1>\n";
        flush();
    }

}


?>
