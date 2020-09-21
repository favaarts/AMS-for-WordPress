
## Preface

When I started this file, I started copy-pasting relevant things that I would think would be used to create the programs iteration. 

Looking at the files more, I found that there were sections that were in different files that were nearly identical, and one of the files see med to have all of the required code. 
To put it briefly, I couldn't even understand which piece of code was actually being called. Part of that is that I don't really understand the lifecycle and file rules to code for Wordpress. 

So, what I am writing instead is how the next iteration should be done (if another person takes over for me).

## Events (also known as Programs)
The events block will essentially have the the same functionality; we will fetch the information for events and parse it to be shown in a list/grid view. 
This could also be done for Workshops and Classes.

These lists are taken from the results of the API:

#### What do we need to see in the list view

- name
- location
- instructors
- photo (original or medium)
- status 

The events we should see should have the status "Active", or "Cancelled". Events with the status "Finished" should not be seen in the list, but should still show up if someone looks up the event.


In the list view we should be able to see these filters:
- A filter for status 
	- Active
	- Finished
	- Cancelled
	- https://wpd.amsnetwork.ca/api/v3/programs/filters
- A filter for location
	- https://wpd.amsnetwork.ca/api/v3/programs/filters 

#### What do we need to see in the details view

- name 
- description
- location
- instructors
- maximum participants
- early-bird discount
- member enrollment price
- non-member enrollment price
- photo (original or medium)
- status 

### Notes
We will probably have to create a registration form for each event/class/workshop, if we don't already have one. This would maybe be done in AMS directly when they sign up?

## Implementation
- The code for the block "AMS Events" will remain essentially the same as ./assets/js/amsblock.js except for the name.
- We should be able to use ./inc/blocksettings.php for all blocks without problem
- We should be able to use the code from ./inc/productdetails.php for the details view of each event.
- "rules" in ./ams-for-wordpress.php are used to make the link structure possible. 

### categoryequipment.php
- Creates the layout for the search box
- Creates the layout for the categories
- Creates the layout for each item card in the list
- Shows the infinite loader

### ams-for-wordpress.php
- Creates initial constants and fetches the style
- Creates the settings and its requests
	- Deals with the token when the plugin is deactivated
- Establishes "rules" to be able to create URLs for each equipment and category
- Has a call to get the list of items
- Has a call to get the category names
- Has a call to create the action of the infinite loader
- Has a call to create the details view page

Note: Those last four points reuse the same code four separate times to create the UI (search box, category list, equipment list). We should make that more iterable.



