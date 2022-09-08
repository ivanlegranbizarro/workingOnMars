# Welcome to *Working on Mars*!

Hello, my name is Iván Legrán, and this API is made to try to respond to the challenge launched by the Talent Squad to try to generate visibility for new developers.

**We are in the year 2040**. Humans have begun to colonize Mars. The first companies begin to settle on the planet and, of course, they need workers. How can they do it? How will Mars companies interact with potential candidates from Earth or Mars itself? **HOUSTON, WE HAVE A PROBLEM. IS THE COLLAPSE OF EARTH'S SPACE ADVENTURE?** 
It could be, but luckily, ***Working on Mars appears***; The API that connects companies and candidates no matter what planet they are from.


# Getting started

Please check the official Laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/9.x).

Laravel is such a complete and incredible Framework that in order to test the API you will hardly need to do anything after cloning this repository, since Laravel already comes with everything you need as standard. Even so, I have included a wonderful tool that has helped me generate the documentation for this project: [laravel-request-docs](https://github.com/rakutentech/laravel-request-docs), which will also help us to be able to test the API from the documentation itself.
After downloading the repository you will only have to install the dependencies with the following command:

`composer install` 


 Since this API has an educational orientation and is merely demonstrative, we have preferred to use an SQLite Database to simplify the process. When doing migrations, after cloning the repository and installing dependencies, Laravel should politely complain about the lack of a database.sqlite file, and should also politely offer to create it for you.

## One last warning, this one of a purely scientific nature

Working on Mars has a system so that candidates can subscribe and receive emails every time a company publishes a new job offer. This configuration is done with mailtrap's Email Sandbox system. The problem is that not even the best astrophysicists on earth know if the mailtrap configuration will survive a space trip to Mars...

Ok, no, the configuration to mailtrap will not survive the publication of the project on Github, since, obviously, the .env file will not be published, but you can see it in the code (NewJobRegisteredNotification.php, NewJobRegisteredEvent.php and NewJobRegisteredListener .php) that a system has been built so that candidates are notified by e-mail when a new job is published.

## You don't tell me that on the street, *sonarcloud*

We were going to publish the values returned by the sonarcloud code analysis, but we strongly disagree with their criteria when it comes to issuing warnings. 

Here is an example:

        'street' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'zip' => 'required|string|max:255',
        'state' => 'required|string|max:255',
  
For sonarcloud, that's 4 'Code smell' warnings, since it urges us to make a variable instead of declaring the same information four times. But there are two things that sonarcloud is not taking into account:

1) For something as critical as validations, the code is much more readable this way.
2) They are validations very subject to change. It is possible that later someone wants to change just some value of one of those particular validations. What should be done then, exclude that validation of the variable?

Another factor to take into account is that sonarcloud complains about the HTML that Laravel includes by default, since this does not seem the most appropriate for ebooks... This is an API, we are not even going to use that HTML.

# Postman

## Trouble installing the project? Do you just want to take a look at the documentation?

We have published the documentation in Postman in case you do not have time to install the project or, simply, as a first contact you prefer to take a look at the documentation. You can consult it [here](https://documenter.getpostman.com/view/14387527/VVBWT6LM).
