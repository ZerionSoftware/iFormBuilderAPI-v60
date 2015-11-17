<h2>iFormBuilder api Version 6 (v60)</h2>
<p>PHP library to interact with iFormBuilder api v60 resources</p>
work in progress :)
<ul>
    <li>Start interacting with api quickly (includes generating access tokens).</li>
    <li>Provides familiar ORM layer for mapping iFormBuidler resources.</li>
    <li>Utilize v60 features</li>
</ul>

<h2>How to use</h2>
<p>Add credentials to Creds/Auth.php interface</p>
<pre>
interface Auth {
    /**
     * client key
     */
    CONST CLIENT = "";
    /**
     * secret key
     */
    CONST SECRET = "";
    /**
     * Oauth endpoint
     */
    CONST OAUTH = "https://yourserver.iformbuilder.com/exzact/api/oauth/token";
}
</pre>

<p>Add Profile to Creds/Profile.php interface</p>

<pre>
interface Profile {

    /**
     * client id : profile id assigned in api apps
     */
    CONST ID = "111111";
    /**
     * server :  "https://YOURCOMPANYSERVER.iformbuilder.com/"
     */
    CONST SERVER = "https://server.iformbuilder.com/";
}


</pre>

<p>use zerion_autoload.php.  If using composer, include composer autoload.php</p>
<pre>
require_once 'zerion_autoload.php';
use Iform\Resources\IformResource;
</pre>
<p>Use container to instantiate resources</p>
<pre>
<div>
$pageResource = IformResource::page();
$optionListResource = IformResource::optionList();
$profileResource = IformResource::profile();
$userResource = IformResource::user();
</div>
</pre>
<p>Following will require a parent identifier</p>
<pre>
<div>
$pageId = 123123;
$recordResource = IformResource::record($pageId);
$elementsResource = IformResource::elements($pageId);

$optionListId = 12312345;
$optionsResource = IformResource::options($optionListId);
</div>
</pre>

<h2>Examples</h2>
<pre>
<code>
//return an collection of all pages in profile
$pageResource->fetchAll();

//filter pages by type
$pageResource->where('data_type(="7")')->fetchAll();

//require all fields
$pageResource->withAllFields()->fetchAll();

//single
$pageId = 123123;
$page = $pageResource->fetch($pageId);

//update
$values = ['name' => 'new_test'];
$pageId = 123123;
$pageResource->update($pageId, $values);

//create page
$values = ['name' => 'test', 'label' => 'This is a test'];
$pageId = $pageResource->create($values);

//delete
$pageId = 123123;
$pageResource->delete($pageId);

//fetch email alerts for page
$pageId = 123123;
$pageResource->alerts($pageId)->fetchAll();

//fetch callbacks for page
$pageId = 123123;
$pageResource->http($pageId)->fetchAll();

//fetch localizations for page
$pageId = 123123;
$pageResource->localizations($pageId)->fetchAll();

//fetch assignments for page
$pageId = 123123;
$pageResource->assignments($pageId)->fetchAll();

//all methods consist for attributes
$values = [
        "language_code"=> "es",
        "label"=> "inspección de la construcción"
];

$pageResource->localizations($pageId)->create($values);
</code>
</pre>
