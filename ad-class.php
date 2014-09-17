<?php
// Get new instance of PDO object
$dbc = new PDO('mysql:host=127.0.0.1;dbname=ads', 'nicole', 'bakagaki');

// Tell PDO to throw exceptions on error
$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// echo $dbc->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "\n";

// error_reporting(E_ALL);

// $ads = 

if(!empty($_POST)){
    $ad = [
        'title' => $_POST['title'],
        'body' => $_POST['body'],
        'contact_name' => $_POST['contact_name'],
        'contact_email' => $_POST['contact_email']
    ];
    $query = $dbc->prepare('INSERT INTO ads (title, body, contact_name, contact_email) VALUES (:title, :body, :contact_name, :contact_email)');

    $query->bindValue(':title', $ad['title'], PDO::PARAM_STR);
    $query->bindValue(':body', $ad['body'], PDO::PARAM_STR);
    $query->bindValue(':contact_name', $ad['contact_name'], PDO::PARAM_STR);
    $query->bindValue(':contact_email', $ad['contact_email'], PDO::PARAM_STR);
    $query->execute();
}

$ads = $dbc->query('SELECT * FROM ads')->fetchAll(PDO::FETCH_ASSOC);
//$query->execute();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Super Ad website - practice in Javascript</title>
</head>
<script src="/js/jquery.js"></script>
<script src="/js/underscore.js"></script>
<script src="/js/backbone.js"></script>

<body>
   <div class="container" id="AdListView">
        <h1>Super Ad site <small>Javascript Practice</small></h1>

            <?php foreach ($ads as $ad): ?>
                <?= $ad['title']; ?><br>
                <?= $ad['body']; ?><br>
                <?= $ad['contact_name']; ?><br>
                <?= $ad['contact_email']; ?><br></br>
            <?php endforeach ?>
    <div class="container" id="createNewAd">
        <h1>Create a New Ad</h1>
        <form role="form" method="POST">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="A title for your ad">
            </div>
            <div class="form-group">
                <label for="body">Body</label>
                <textarea class="form-control" id="body" name="body" rows="6"></textarea>
            </div>
            <div class="form-group">
                <label for="contact_name">Contact Name</label>
                <input type="text" class="form-control" id="contact_name" name="contact_name" placeholder="Who you gonna call?">
            </div>
            <div class="form-group">
                <label for="contact_email">Contact Email</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" placeholder="Email address to contact at">
            </div>
            <!-- <button type="submit" id="createNewAd" class="btn btn-primary" style="cursor: pointer; text-decoration: underline"> -->
                <!-- Create new ad!</button> -->

        </form>
    </div>

<script>

(function($){
    Backbone.sync = function(method, model, success, error){
            success();
    }

    var Ad = Backbone.Model.extends({
        defaults: {
            title: 'title',
            body: 'body',
            contactName: 'contact_name',
            contactEmail: 'contact_email'
            // createdAt: ""
        }
    });
    
    var Ads = Backbone.Collection.extend({
        model: Ad
    });

    var AdView = Backbone.View.extend({
        events: {
            'click h1.show': 'show',
            'click h1.hide': 'hide'
        },

        initialize: function(){
            _.bindAll(this, 'render', 'unrender', 'edit', 'remove');
            this.model.bind('edit', this.render);
            this.model.bind('remove', this.unrender);
        },
        render: function(){
            $(this.el).html('<h2>' + this.model.get('title') + '</h2><br>'
            + '<p>' + this.model.get('body') + '<br>'
            + this.model.get('contactName') + '<br>'
            + this.model.get('contactEmail') + '<br>'
            + this.model.get('createdAt') + '<br></p>');
            return this;
        },

        unrender:function(){
            $(this.el).remove();
        },
        edit:function(){
            $(this.el).edit(); //figure this out later
        },
        remove: function(){
            this.model.destroy();
        }

    });

    var AdListView = Backbone.View.extend({
        el:$('body'),

        events:{
            'click button#createNewAd': 'createNewAd'
        },

        initialize: function(){
            _.bind(this, 'render', 'createNewAd', 'appendNewAd');
            this.collection = new AdList();
            this.collection.bind('createNewAd');
            this.render();
        },
        render: function(){
            var self = this;
            $(this.el).append("<button id='createNewAd'> Create New Ad</button>");
            $(this.el).append("<ul></ul>");
            $(this.el).show('createNewAd');
            _(this.collection.models).each(function(item){
                self.appendNewAd(ad);
            }, this);
        },

        createNewAd: function(){
            var ad = new Ad();
            ad.set({
                title,
                body,
                contactName,
                contactEmail
            //     createdAt
            // });
            this.collection.add(ad);
        },

        appendNewAd: function(ad){
            var AdListView = new AdListView({
                model: ad
            });
            $('ul', this.el).append(adView.render().el);

        }
    });
    var AdListView = new AdListView;

})(jQuery);
</script>

</body>
</html>