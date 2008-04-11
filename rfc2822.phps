<?php

	#
	# RFC(2)822 Email Parser
	#
	# By Cal Henderson <cal@iamcal.com>
	# This code is licensed under a Creative Commons Attribution-ShareAlike 2.5 License
	# http://creativecommons.org/licenses/by-sa/2.5/
	#
	# $Revision: 1.1 $
	#

	##################################################################################

	function is_valid_email_address($email){


		####################################################################################
		#
		# NO-WS-CTL       =       %d1-8 /         ; US-ASCII control characters
		#                         %d11 /          ;  that do not include the
		#                         %d12 /          ;  carriage return, line feed,
		#                         %d14-31 /       ;  and white space characters
		#                         %d127
		# ALPHA          =  %x41-5A / %x61-7A   ; A-Z / a-z
		# DIGIT          =  %x30-39

		$no_ws_ctl	= "[\\x01-\\x08\\x0b\\x0c\\x0e-\\x1f\\x7f]";
		$alpha		= "[\\x41-\\x5a\\x61-\\x7a]";
		$digit		= "[\\x30-\\x39]";
		$cr		= "\\x0d";
		$lf		= "\\x0a";
		$crlf		= "($cr$lf)";


		####################################################################################
		#
		# obs-char        =       %d0-9 / %d11 /          ; %d0-127 except CR and
		#                         %d12 / %d14-127         ;  LF
		# obs-text        =       *LF *CR *(obs-char *LF *CR)
		# text            =       %d1-9 /         ; Characters excluding CR and LF
		#                         %d11 /
		#                         %d12 /
		#                         %d14-127 /
		#                         obs-text
		# obs-qp          =       "\" (%d0-127)
		# quoted-pair     =       ("\" text) / obs-qp

		$obs_char	= "[\\x00-\\x09\\x0b\\x0c\\x0e-\\x7f]";
		$obs_text	= "($lf*$cr*($obs_char$lf*$cr*)*)";
		$text		= "([\\x01-\\x09\\x0b\\x0c\\x0e-\\x7f]|$obs_text)";
		$obs_qp		= "(\\x5c[\\x00-\\x7f])";
		$quoted_pair	= "(\\x5c$text|$obs_qp)";


		####################################################################################
		#
		# obs-FWS         =       1*WSP *(CRLF 1*WSP)
		# FWS             =       ([*WSP CRLF] 1*WSP) /   ; Folding white space
		#                         obs-FWS
		# ctext           =       NO-WS-CTL /     ; Non white space controls
		#                         %d33-39 /       ; The rest of the US-ASCII
		#                         %d42-91 /       ;  characters not including "(",
		#                         %d93-126        ;  ")", or "\"
		# ccontent        =       ctext / quoted-pair / comment
		# comment         =       "(" *([FWS] ccontent) [FWS] ")"
		# CFWS            =       *([FWS] comment) (([FWS] comment) / FWS)

		#
		# note: we translate ccontent only partially to avoid an infinite loop
		# instead, we'll recursively strip comments before processing the input
		#

		$wsp		= "[\\x20\\x09]";
		$obs_fws	= "($wsp+($crlf$wsp+)*)";
		$fws		= "((($wsp*$crlf)?$wsp+)|$obs_fws)";
		$ctext		= "($no_ws_ctl|[\\x21-\\x27\\x2A-\\x5b\\x5d-\\x7e])";
		$ccontent	= "($ctext|$quoted_pair)";
		$comment	= "(\\x28($fws?$ccontent)*$fws?\\x29)";
		$cfws		= "(($fws?$comment)*($fws?$comment|$fws))";
		$cfws		= "$fws*";


		####################################################################################
		#
		# atext           =       ALPHA / DIGIT / ; Any character except controls,
		#                         "!" / "#" /     ;  SP, and specials.
		#                         "$" / "%" /     ;  Used for atoms
		#                         "&" / "'" /
		#                         "*" / "+" /
		#                         "-" / "/" /
		#                         "=" / "?" /
		#                         "^" / "_" /
		#                         "`" / "{" /
		#                         "|" / "}" /
		#                         "~"
		# atom            =       [CFWS] 1*atext [CFWS]

		$atext		= "($alpha|$digit|[\\x21\\x23-\\x27\\x2a\\x2b\\x2d\\x2f\\x3d\\x3f\\x5e\\x5f\\x60\\x7b-\\x7e])";
		$atom		= "($cfws?$atext+$cfws?)";


		####################################################################################
		#
		# qtext           =       NO-WS-CTL /     ; Non white space controls
		#                         %d33 /          ; The rest of the US-ASCII
		#                         %d35-91 /       ;  characters not including "\"
		#                         %d93-126        ;  or the quote character
		# qcontent        =       qtext / quoted-pair
		# quoted-string   =       [CFWS]
		#                         DQUOTE *([FWS] qcontent) [FWS] DQUOTE
		#                         [CFWS]
		# word            =       atom / quoted-string

		$qtext		= "($no_ws_ctl|[\\x21\\x23-\\x5b\\x5d-\\x7e])";
		$qcontent	= "($qtext|$quoted_pair)";
		$quoted_string	= "($cfws?\\x22($fws?$qcontent)*$fws?\\x22$cfws?)";
		$word		= "($atom|$quoted_string)";


		####################################################################################
		#
		# obs-local-part  =       word *("." word)
		# obs-domain      =       atom *("." atom)

		$obs_local_part	= "($word(\\x2e$word)*)";
		$obs_domain	= "($atom(\\x2e$atom)*)";


		####################################################################################
		#
		# dot-atom-text   =       1*atext *("." 1*atext)
		# dot-atom        =       [CFWS] dot-atom-text [CFWS]

		$dot_atom_text	= "($atext+(\\x2e$atext+)*)";
		$dot_atom	= "($cfws?$dot_atom_text$cfws?)";


		####################################################################################
		#
		# domain-literal  =       [CFWS] "[" *([FWS] dcontent) [FWS] "]" [CFWS]
		# dcontent        =       dtext / quoted-pair
		# dtext           =       NO-WS-CTL /     ; Non white space controls
		# 
		#                         %d33-90 /       ; The rest of the US-ASCII
		#                         %d94-126        ;  characters not including "[",
		#                                         ;  "]", or "\"

		$dtext		= "($no_ws_ctl|[\\x21-\\x5a\\x5e-\\x7e])";
		$dcontent	= "($dtext|$quoted_pair)";
		$domain_literal	= "($cfws?\\x5b($fws?$dcontent)*$fws?\\x5d$cfws?)";


		####################################################################################
		#
		# local-part      =       dot-atom / quoted-string / obs-local-part
		# domain          =       dot-atom / domain-literal / obs-domain
		# addr-spec       =       local-part "@" domain

		$local_part	= "($dot_atom|$quoted_string|$obs_local_part)";
		$domain		= "($dot_atom|$domain_literal|$obs_domain)";
		$addr_spec	= "($local_part\\x40$domain)";


		#
		# we need to strip comments first (repeat until we can't find any more)
		#

		$done = 0;

		while(!$done){
			$new = preg_replace("!$comment!", '', $email);
			if (strlen($new) == strlen($email)){
				$done = 1;
			}
			$email = $new;
		}


		#
		# now match what's left
		#

		return preg_match("!^$addr_spec$!", $email) ? 1 : 0;
	}

	##################################################################################

	function test($email){

		echo "<tr><td>".HtmlEntities($email)."</td>";
		echo "<td>".(is_valid_email_address($email)?'Yes':'No')."</td></tr>";
	}

	##################################################################################
	
?>

<table border="1">
	<tr>
		<th>Input</th>
		<th>Valid?</th>
	</tr>
<?php test('cal@iamcalx.com'); ?>
<?php test('cal+henderson@iamcalx.com'); ?>
<?php test('cal henderson@iamcalx.com'); ?>
<?php test('"cal henderson"@iamcalx.com'); ?>
<?php test('cal@iamcalx'); ?>
<?php test('cal@iamcalx com'); ?>
<?php test('cal@hello world.com'); ?>
<?php test('cal@[hello].com'); ?>
<?php test('cal@[hello world].com'); ?>
<?php test('cal@[hello\\ world].com'); ?>
<?php test('cal@[hello.com]'); ?>
<?php test('cal@[hello world.com]'); ?>
<?php test('cal@[hello\\ world.com]'); ?>
<?php test('abcdefghijklmnopqrstuvwxyz@abcdefghijklmnopqrstuvwxyz'); ?>

<?php test('woo\\ yay@example.com'); ?>
<?php test('woo\\@yay@example.com'); ?>
<?php test('woo\\.yay@example.com'); ?>

<?php test('"woo yay"@example.com'); ?>
<?php test('"woo@yay"@example.com'); ?>
<?php test('"woo.yay"@example.com'); ?>
<?php test('"woo\\"yay"@test.com'); ?>

<?php test('webstaff@redcross.org'); ?>

<?php test('user@???'); ?>

<?php test('user.@domain.com'); ?>

</table>