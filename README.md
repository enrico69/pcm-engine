pcm-engine
==========

Ready to use and powerfull POO PHP engine, great to developp your website or web-based application.

= What is it exactly ? =

pcm-engine is a ready to use engine for web-based application. If you choose this solution to developp
you site/app, you will be located between a "start-from-zero" dev and a framework.

pcm-engine is not a framework, but is close to be. You have just to download this repository, open the config/config.php file
to edit your configuration, and that's it!

= What is it intended for ? =

It is intended for developpers who want to create a new website or application, without to have to create everything,
developpers who want to use a full POO system, with clear separation between layers, but who want a lighter system
than the greats (but far more powerfull and complete) frameworks.

= How does-it work ? =

pcm-engine is a near MVC system. All the request are send to a front-controller which choose the good controller. 
You can select one of the three routes management system :
- Direct system : ex: www.your-site.com/controller/method
- Static route system, using a xml route file
- Your own system : pcm-engine include an empty module file you have to implement the way you want. Just return an array
with the name of the controller to instanciate and the method to call

= What about the database ? =

Using the database is very simple : a connector using PDO for mySQL is already implemented. It use an abstract class to guide
dev who want to developp their own system.
The included system allow to exchange objects easily with the database : you have just to create your request.
On top of that, common requests are already in, like "Select All", "Save", "Delete", "Last" and "Find".
You just to have to respect the naming convention. Follow the exemple given in the repository.

= Does-it include a CMS ? =

No. As said previously, it is an engine. However, for some projets, I developped a light CMS for it. This is why there
is the third URL managing system with the empty file to be implemented (see above).

= Where can I find some documentation ? =
Just here, on this GitHub repository. 
You can also contact me by email!

= Contact =
Email : e.courtial [at] hotmail.fr

= Licence =
Do whatever you want with this stuff, but I am not responsible if you destroy the planet earth.
Just send me an email if you select this solution for you project, to see how I can improve it based on you work.
By the way : MIT licence