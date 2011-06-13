<?
	$title = "RFC 822 Email Address Parser in PHP";
	include('../../head.txt');
?>

<h1>RFC 822  Email Address Parser in PHP</h1>


<h2>Source code</h2>

<ul>
	<li> <a href="http://github.com/iamcal/rfc822/blob/master/rfc822.php">rfc822.php</a> - Email Address Parser </li>
</ul>

<h2>Download</h2>

<p>There is an <a href="/php/rfc822/demo.php">online interactive demo</a> if you'd just like to give it a try.</p>

<p>You can download the latest stable version (release 11) of the functions <a href="http://github.com/downloads/iamcal/rfc822/rfc822_r11.zip">here</a>. </p>

<p>The very latest versions are available from the <a href="http://github.com/iamcal/rfc822">GitHub repository</a>. </p>


<h2>Tests</h2>

<p>The <a href="tests/">test suite</a> shows parser's results, based on these <a href="tests/tests.xml">test definitions</a>. These are borrowed from
<a href="http://www.dominicsayers.com/isemail/">Dominic Sayers</a> who has a similar parser. We are still arguing over certain tests ;)</p>


<h2>The RFCs</h2>

<p>This library was named back when there was only one RFC for email addresses; there are now lots, so it would be better named RFC 822/2822/5322 at the least. These are the most relevant 
ones:</p>

<dl>
	<dt><a href="http://www.faqs.org/rfcs/rfc821.html">RFC 821 - Simple Mail Transfer Protocol</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=821">Errata</a>)</dt>
	<dd>The original SMTP RFC</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc822.html">RFC 822 - Standard for the Format of ARPA Internet Text Messages</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=822">Errata</a>)</dt>
	<dd>The original 'email' RFC</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc1035.html">RFC 1035 - Domain names - implementation and specification</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=1035">Errata</a>)</dt>
	<dd>The old domains RFC</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc1123.html">RFC 1123 - Requirements for Internet Hosts - Application and Support</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=1123">Errata</a>)</dt>
	<dd>An update to RFC 1035</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc2821.html">RFC 2821 - Simple Mail Transfer Protocol</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=2821">Errata</a>)</dt>
	<dd>SMTP contains some address limits not in RFC 2822</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc2822.html">RFC 2822 - Internet Message Format</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=2822">Errata</a>)</dt>
	<dd>Superceeds RFC 822</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc3696.html">RFC 3696 - Application Techniques for Checking and Transformation of Names</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=3696">Errata</a>)</dt>
	<dd>An informative RFC that clarifies some rules (and muddies others)</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc4291.html">RFC 4291 - IP Version 6 Addressing Architecture</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=4291">Errata</a>)</dt>
	<dd>Some useful details about the horrors of IPv6</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc5321.html">RFC 5321 - Simple Mail Transfer Protocol</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=5321">Errata</a>)</dt>
	<dd>Superceeds RFC 2821 (this is the latest SMTP RFC)</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc5322.html">RFC 5322 - Internet Message Format</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=5322">Errata</a>)</dt>
	<dd>Superceeds RFC 2822 (this is the latest email RFC)</dd>

	<dt><a href="http://www.faqs.org/rfcs/rfc5952.html">RFC 5952 - A Recommendation for IPv6 Address Text Representation</a> (<a href="http://www.rfc-editor.org/errata_search.php?rfc=5952">Errata</a>)</dt>
	<dd>Superceeds RFC 4291 (this is the latest IPv6 RFC)</dd>
</dl>

<p>Reading the errata is pretty important, since some of the examples and even the EBNF are wrong in the original RFCs.</p>


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

<p>
	The code only verifies that the email address matches the various RFC specs.
	<b>This does not mean it's a valid Internet email address!</b>
	For an email address to be valid on the Internet, the domain part must be a valid domain name, be resolvable and have an MX.
	The code will identify the address "<code>foo@bar.baz</code>" as valid, even though we konw that there's no such domain as <code>bar.baz</code>.
	If you want to check that it's valid, fetching the MX for the domain is a good start.
	Connecting to the MX to verify it's a mail server is even better.
</p>


<h2>Extras</h2>

<p>Tim Fletcher has translated the function to ruby and python: <a href="http://tfletcher.com/lib/">http://tfletcher.com/lib/</a>. </p>

<p>
	A fullly unpacked version of the underlying regular expression can be seen <a href="full_regexp.txt">here</a>. It's huge.
</p>

<p>
	It's been said that it's impossible to parse email addresses using regular expressions alone. This is somewhat true.
	If you allow comments in email addresses, then nested comments cannot be matched with a single regexp - a simple loop applying a reducing regexp first is needed.
	Aside from that, this library uses some post-match checks instead of rolling everything into one regexp.
	This is not because it wouldn't be possible, but because it would make it <i>huge</i> - the number of IPv6 permutations alone would probably double the size.
	Aside from the practicality, it seems entirely possible to boil it down to a single regexp.
	However, the <a href="http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state">one used for HTML5</a> is not even close :(
</p>


<?
	include('../../foot.txt');
?>
