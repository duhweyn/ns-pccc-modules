Drop your secondary/billing logo file in this folder, named exactly:

    billing-logo.png
    billing-logo.jpg
    billing-logo.jpeg
    billing-logo.svg

(any one of the above — only the first match found is used, in that order).

No further setup needed: header_section.blade.php reads whichever file is
present here at render time and inlines it directly into the receipt, so
there's nothing to publish, symlink, or configure in NexoPOS settings.

Delete the file (or don't add one) to render the section without a logo.
