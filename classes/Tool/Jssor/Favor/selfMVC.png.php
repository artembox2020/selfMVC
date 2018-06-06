<img style='width : 100%; height : 440px;' src ='<?= $dirUrl ?>/selfMVC.png' />
<br/>
<h3 align=center>Short description and info</h3>
<div class='row'>
    <div class = 'col-md-12 col-xs-12 col-sm-12'>
        <p>
            <b>selfMVC framework:</b> this is the fully functional MVC framework, based on the main principles of MVC framework creation.
            It offers for some new features :
            <ul>
                <li>Widget code incapsulation, all scripts and styles all at the same place, widget module</li>
                <li>New improved database engine, based on PDO</li>
                <li>Pre-set classes for the text processing, popup window and slider</li>
            </ul>
        </p>
        <p>Besides <b>selfMVC framework</b> is very easy and fast and it is the most appropriate decision for small and medium businesses</p>
    </div>
</div>
<h3 align=center>Details overview</h3>
<div class="row">
    <div class="col-md-12 col-xs-12 col-sm-12">
       <p>
           <ul>
               <li>Database engine</li>
           </ul>
           <p><b>selMVC framework</b> presents the new database engine, based on typical PDO engine. But, despite the last, my new DBH technology represents the new and convenient way to make sql queries. It implements Object oriented ideology to build the queries. For example, there is the separate class to build relations, separate class to build query conditions, separate class to incapsulate the previos ones and make target queries. You also could write the code in pure sql, but make such a queries much more simple. All methods are safe-context, they contain protection against sql injection.</p>
           <p>Here is example of how it works</p>
           <p style="font-style: italic;">
               /* initialize TestWorker */<br/>
               $test = new TestWorker();<br/>
               /* initialize relation object, joining two tables */<br/>
               $rel = new DBHRel("ONE_MANY","users_reports",["name"=>"users","users"=>"id","fields"=>["id","login","date"]], ["name"=>"users_reports","users_reports"=>"user_id","fields"=>["month","day","comment"]]);<br/>
               /* initialize condition criteria object */<br/>
               $criteria = new DBHCriteria("users_reports.user_id >?",[0],"users_reports.user_id DESC");<br/>
               /* find results */ <br/>
               $result= $test->findByAttrRel($rel,$criteria,['one' => true]);<br/>
               
           </p>
           
           <p>You may not only find necessary data, but and also insert, update or delete it, thus, DBH engine represents  all CRUD operations.</p>
       </p> 
       <p>
           <ul>
               <li>Improved controller rendering and templating</li>
           </ul>
           <p>You may render any other controller from your current controller and dynamically set the template, which you want. It is very easy and flexible. </p>
           <p style="font-style: italic;">
               /* renders otherController->otherAction() method from your controller */<br/>
               public function testAction() { <br/>
                 $this->renderController('other','other', [], 'main');<br/>
                 }<br/>
           </p>
       </p>
       <p>
           <ul>
               <li>Pre-set auxiliary classes</li>
           </ul>
           <p>Framework includes some classes to simplify some often used functionality. So, you can quickly build sliders and popup windows, encrypt and decrypt your any data, both in PHP and JS. Later the list will be continued with a lot of other useful options</p>
       </p>
    </div>
</div>
<h3 align=center>What is later</h3>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <p>In the later version of my framework you will have pre-set classes for the user authorization and registration, the separate class to simplify the deal with session. You may also offer me your own idea how to improve framework. See new offers and improvements at my site. If you have any proposition or claim, please contact me at the top side of this site. Thanks for the time devoted and have a nice day!</p>
    </div>
</div>
<h3 align= center>Download</h3>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <p>Here is the link to Github project, see details in README.txt file</p>  
    </div>
</div>