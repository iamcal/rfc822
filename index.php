<?
	$title = "RFC (2)822 &amp; 3696 Email Address Parser in PHP";
	include('../../head.txt');
?>

<h1>RFC (2)822 &amp; 3696 Email Address Parser in PHP</h1>

<p><span style="background-color: yellow"><b>Warning!</b> Releases 4 and older contained a bug in the RFC2882 <code>atext</code> rule, which allowed periods in the wrong places. Upgrade to release 5 or later to fix this issue.</span></p>


<h2>Source code</h2>

<ul>
	<li> <a href="rfc822.phps">RFC 822 Parser</a> </li>
	<li> <a href="rfc2822.phps">RFC 2822 Parser</a> </li>
	<li> <a href="rfc3696.phps">RFC 3696 Parser</a> (you probably want this one) </li>
</ul>


<h2>Tests</h2>

<p>The <a href="tests.php">test suite</a> shows results for each parser, based on these <a href="tests.xml">test definitions</a>. These are borrowed from <a href="http://www.dominicsayers.com/isemail/">Dominic Sayers</a> who has a similar parser. We are still arguing over certain tests ;)</p>


<h2>Download</h2>

<p>You can download the latest version (release 9) of the functions <a href="rfc822_r9.zip">here</a>. </p>

<p>The very latest versions are available from the <a href="https://svn.iamcal.com/public/php/rfc822/trunk/">SVN repository</a>. </p>


<h2>The RFCs</h2>

<p>Email address formats are covered by several RFCs:</p>

<dl>
	<dt><a href="http://www.faqs.org/rfcs/rfc822.html">RFC 822 - Standard for the Format of ARPA Internet Text Messages</a></dt>
	<dd>The original 'email' RFC</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc2821.html">RFC 2821 - Simple Mail Transfer Protocol</a></dt>
	<dd>SMTP contains some address limits not in RFC2822</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc2822.html">RFC 2822 - Internet Message Format</a></dt>
	<dd>Superceeds RFC822</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc3696.html">RFC 3696 - Application Techniques for Checking and Transformation of Names</a></dt>
	<dd>An informative RFC that clarifies some rules (and muddies others)</dd>
</dl>


<h2>Copyright</h2>

<p>By Cal Henderson &lt;cal@iamcal.com&gt;</p>
<p>
	This code is dual licensed:<br />
	Creative Commons Attribution-ShareAlike 2.5 License - <a href="http://creativecommons.org/licenses/by-sa/2.5/">http://creativecommons.org/licenses/by-sa/2.5/</a><br />
	GNU General Public License v3 - <a href="http://www.gnu.org/copyleft/gpl.html">http://www.gnu.org/copyleft/gpl.html</a><br />
</p>
<p>
	If you require the code to be released under a different license, please contact the author.
</p>


<h2>Limitations</h2>

<p>The code only verifies that the email address matches the RFC spec. <b>This does not mean it's a valid Internet email address!</b> For an email address to be valid on the Internet, the domain part must be a valid domain name, be resolvable and have an MX. The code will identify the address "<code>foo@bar.baz</code>" as valid, even though we konw that there's no such domain as <code>bar.baz</code>. If you want to check that it's valid, fetching the MX for the domain is a good start. Connecting to the MX to verify it's a mail server is even better.</p>


<h2>Extras</h2>

<p>Tim Fletcher has translated the function to ruby and python: <a href="http://tfletcher.com/lib/">http://tfletcher.com/lib/</a>. </p>

<p>A full rolled-up version of the RFC 2882 regexp can be seen <a href="full_regexp.txt">here</a>. </p>


<?
	include('../../foot.txt');
?>