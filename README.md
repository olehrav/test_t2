# Test App

## Setup

### Technologies assumed

-   > php 7.x.x

### Steps to take

1.  Run 'composer install' command.
2.  Run in CL command 'php console' to see more details about application.

### Examples 

1.    php console cart:process -- mbp "Macbook Pro" 1 1000 USD     -    add product with id - mbp ; name - Macbook Pro ; price 1000 USD into the shopping cart
2.    php console cart:process -- mbp "Macbook Pro" -1 1000 USD     -    remove product with id - mbp from shopping cart