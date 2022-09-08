# Welcome to *Working on Mars*!

Hello, my name is Iván Legrán, and this API is made to try to respond to the challenge launched by the Talent Squad to try to generate visibility for new developers.

**We are in the year 2040**. Humans have begun to colonize Mars. The first companies begin to settle on the planet and, of course, they need workers. How can they do it? How will Mars companies interact with potential candidates from Earth or Mars itself? **HOUSTON, WE HAVE A PROBLEM. IS THE COLLAPSE OF EARTH'S SPACE ADVENTURE?** 
It could be, but luckily, ***Working on Mars appears***; The API that connects companies and candidates no matter what planet they are from.


# Getting started

Laravel is such a complete and incredible Framework that in order to test the Api you will hardly need to do anything after cloning this repository, since Laravel already comes with everything you need as standard. Even so, I have included a wonderful tool that has helped me generate the documentation for this project: [laravel-request-docs](https://github.com/rakutentech/laravel-request-docs), which will also help us to be able to test the api from the documentation itself.
After downloading the repository you will only have to install the dependencies with the following command:

composer install

## Some orientation

I have redirected the main link of the project to the documentation page so that at a glance you can see all the Endpoints and test them.

All routes, except those that have to be intrinsically public to register companies, candidates or to login to them, are protected with Laravel Sanctum.

## Oh, a boring (but important) warning about Databases

As this is a project with some complexity, but with a purely educational and demonstration approach, we have preferred to use an SQLite Database to simplify the process. When doing migrations, after cloning the repository and installing dependencies, Laravel should politely complain about the lack of a database.sqlite file, and should also politely offer to create it for you.

## One last warning, this one of a purely scientific nature

Working on Mars has a system so that candidates can subscribe and receive emails every time a company publishes a new job offer. This configuration is done with mailtrap's Email Sandbox system. The problem is that not even the best astrophysicists on earth know if the mailtrap configuration will survive a space trip to Mars...

Ok, no, the configuration to mailtrap will not survive the publication of the project on Github, since, obviously, the .env file will not be published, but you can see it in the code (NewJobRegisteredNotification.php, NewJobRegisteredEvent.php and NewJobRegisteredListener .php) that a system has been built so that candidates are notified by e-mail when a new job is published.