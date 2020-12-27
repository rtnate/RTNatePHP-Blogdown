# RTNatePHP-Blogdown
Static file based website/blog content loader for PHP.


## Valid Blogdown Files

#### Markdown (.md)
#### HTML (.html)
#### JSON (.json)
#### YAML (.yaml)



## Directory Structure
Blogdown is a Jekyll inspired project that uses the following basic directory
structure.

### Page Directories

A page directory is any directory preceded with an underscore ("_") and 
excluding any of the Reserved Items.  A page directory represents a single
page with multiple files containing data.  Blogdown will look for a content
file with the same name as the directory (less the underscore).  All
files in the _data directory will also be loaded

### Reserved items

#### _static
A reserved folder that will/should be symlinked into public and resolved
accordingly where assets will be served statically (not managed by PHP/Blogdown).

#### _data
A reserved folder whose contents will be parsed and any valid blogdown files 
will be loaded and made available to *ALL* pages within the current directory
and all subdirectories

#### _blog
A reserved folder that is the default location of Blogdown's blog

### Main Directory
The main content directory is treated as the content root of the site.
The site router will direct '/' to this directory and resolve routes and pages
based on the following:

All subdirectories not named with a leading underscore will be treated as
routes so {content-root}/folder/file.md will be available at /folder/file/.

{content-root}/folder/index.md will be available at /folder/ and /folder/index.

## Blog
The blog folder by default will be {content-root}/_blog, though this can
be configured otherwise.  The blog folder should have the following structure:

- {blog-root}/_data : Data files containing content that will available to all 
blog views

- {blog-root}/_posts: All active posts should be located here (see Posts)

- {blog-root}/_drafts: Draft posts that may be previewed but aren't active
(see Drafts)

- {blog-root}/_deleted: Deleted posts that may be previews but aren't active
(see Deleted Posts)

- {blog-root}/_index or {blog-root}/index.{md,html,etc}: Content that will be
available to the root page of the blog.

### Posts
See https://jekyllrb.com/docs/posts/
