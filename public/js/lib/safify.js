;/* safify.jquery.js
 *
 * https://github.com/jamiesonbecker/jquery-safify
 * Copyright (c) 2013 Jamieson Becker, http://jamiesonbecker.com
 * MIT License
 * 
 */

function strict_safify(htm) {

    // this prevents possible HTML attacks using the absolutely bare-minimum
    // essentials as specified by OWASP
    // https://www.owasp.org/index.php/XSS_%28Cross_Site_Scripting%29_Prevention_Cheat_Sheet
    // this method of pure string replacements are much safer and faster than
    // attempting to regex-match all tags

    // See jQuery plugin at end for example usage

    var htm = htm || "";
    return htm
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#x27;')
        .replace(/\//g, '&#x2F;')
        .replace(/\n/g, ' <br> ');

}

function safify(htm) {

    // This runs normal strict_safify but puts back in
    // a few safer HTML tags. No attributes are currently allowed.
    // uncomplicated regex -- easy to audit
    // images coming soon! (src= can be dangerous)
    // This may eventually be supplemented with a custom version of
    // https://github.com/cowboy/javascript-linkify with rel=nofollow

    return strict_safify(htm)
        // bold
        .replace(/&lt;b&gt;/gi, " <b> ")
        .replace(/&lt;&#x2F;b&gt;/gi, " </b> ")
        // strong
        .replace(/&lt;strong&gt;/gi, " <strong> ")
        .replace(/&lt;&#x2F;strong&gt;/gi, " </strong> ")
        // emphasis
        .replace(/&lt;em&gt;/gi, " <em> ")
        .replace(/&lt;&#x2F;em&gt;/gi, " </em> ")
        // italics
        .replace(/&lt;i&gt;/gi, " <i> ")
        .replace(/&lt;&#x2F;i&gt;/gi, " </i> ")
        // li
        .replace(/&lt;li&gt;/gi, " <li> ")
        .replace(/&lt;&#x2F;li&gt;/gi, " </li> ")
        // ul
        .replace(/&lt;ul&gt;/gi, " <ul> ")
        .replace(/&lt;&#x2F;ul&gt;/gi, " </ul> ")
        // ol
        .replace(/&lt;ol&gt;/gi, " <ol> ")
        .replace(/&lt;&#x2F;ol&gt;/gi, " </ol> ")
        // h1
        .replace(/&lt;h1&gt;/gi, " <h1> ")
        .replace(/&lt;&#x2F;h1&gt;/gi, " </h1> ")
        // h2
        .replace(/&lt;h2&gt;/gi, " <h2> ")
        .replace(/&lt;&#x2F;h2&gt;/gi, " </h2> ")
        // h3
        .replace(/&lt;h3&gt;/gi, " <h3> ")
        .replace(/&lt;&#x2F;h3&gt;/gi, " </h3> ")
        // h4
        .replace(/&lt;h4&gt;/gi, " <h4> ")
        .replace(/&lt;&#x2F;h4&gt;/gi, " </h4> ")
        // h5
        .replace(/&lt;h5&gt;/gi, " <h5> ")
        .replace(/&lt;&#x2F;h5&gt;/gi, " </h5> ")
        // h6
        .replace(/&lt;h6&gt;/gi, " <h6> ")
        .replace(/&lt;&#x2F;h6&gt;/gi, " </h6> ")
        // table
        .replace(/&lt;table&gt;/gi, " <table> ")
        .replace(/&lt;&#x2F;table&gt;/gi, " </table> ")
        // tr
        .replace(/&lt;tr&gt;/gi, " <tr> ")
        .replace(/&lt;&#x2F;tr&gt;/gi, " </tr> ")
        // td
        .replace(/&lt;td&gt;/gi, " <td> ")
        .replace(/&lt;&#x2F;td&gt;/gi, " </td> ")
        // p
        .replace(/&lt;p&gt;/gi, " <p> ")
        .replace(/&lt;&#x2F;p&gt;/gi, " </p> ")
        // br
        .replace(/&lt;br&gt;/gi, " <br> ")
        ;
}

function unstrict_safify(htm) {
    // only use this for input or non-HTML textarea plugins
    // (don't use with Javascript HTML editors!)
    var htm = htm || "";
    return htm
        .replace(/<br>/g, '\n')
        .replace(/&amp;/g, '&')
        .replace(/&lt;/g, '<')
        .replace(/&gt;/g, '>')
        .replace(/&quot;/g, '"')
        .replace(/&#x27;/g, "'")
        .replace(/&#x2F;/g, '/');
}


/*
 *  jQuery Safify
 *
 *  Allows some minimal HTML while keeping out the bad stuff.
 *  (If you want to convert all HTML to plain text like pure safify.js, jQuery
 *  already has $.text built-in.)
 *
 *
 *      var evil = "<script>alert(1);</script>";
 *
 *
 *  To use, just replace your normal:
 *
 *
 *      $("div").html(evil);
 *         or:
 *      $("div").text(evil);
 *
 *
 *  with:
 *
 *      $("div").safify(evil);
 *
 *  unsafify() is not wrapped to keep it from being accidentally (mis)used.
*/

(function($) {
    // Allows "safe" tags
    $.fn.safify = function(evil) {
        this.html(safify(evil));
        return this;
    };
    // Allows no tags
    $.fn.strict_safify = function(evil) {
        this.html(strict_safify(evil));
        return this;
    };
})(jQuery);

