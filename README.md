cubits/opencart-plugin
======================


# Cubits-PHP submodule
-------

in the root run

git submodule init

git submodule update



# Installation

Copy these files into your OpenCart directory.

# Configuration

0. Before all copy the 3 folders (admin, catalog and cubits-php) into your opencart shop. (if cubits-php is empty, you must initialise the submodule, instructions are above)

1. Create an API/Secret pair in your merchant account
2. In the opencart administration under Extensions->Payments, click the "Install"
   link on the Cubits row.
3. Also under Extensions->Payments, click the "Edit" link on the Cubits row.
4. Set the API/secret you created in step 1.
5. Set the confirmed status to the order status that you would like to to use
   when a bitcoin payment is confirmed (according to your speed preference).  A
   status of "pending" is typically used.
6. Set the invalid status to the order status that you would like to use when a
    bitcoin payment has been determine to be invalid.
7. Set the invalid pending status.
8. Set the status to enabled (this activates the bitpay payment extension and
    enabled shoppers to select the bitcoin payment method).
9. Select a sort order.  The sort order determines the ordering of payment options
    presented to the shopper.

# Usage

When a shopper chooses the Bitcoin payment method, they will be presented with an
order summary as the next step (prices are shown in whatever currency they've selected
for shopping).  They will be presented with a button called "Pay with Bitcoin."  This
button takes the shopper to a Cubits invoice where the user is presented with
bitcoin payment instructions.  Once payment is received, a link is presented to the
shopper that will take them back to the order summary.

## OpenCart Support

* [Homepage](http://www.opencart.com/)
* [Documentation](http://docs.opencart.com/)
* [Forums](http://forum.opencart.com/)

The MIT License (MIT)

Copyright (c) 2014 Dooga Ltd.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.