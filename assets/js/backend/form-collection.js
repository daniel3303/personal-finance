import $ from 'jquery';

var $collectionHolder;

window.addEventListener("load", function () {
    //Add collection element
    $(".add-collection-element-form").on("click", function (e) {
        e.preventDefault();

        $collectionHolder = $($(this).data("list-selector"));
        addCollectionElementForm($collectionHolder);
    });

    //Remove collection element
    $(".remove-collection-element-form").on("click", function (e) {
        e.preventDefault();

        $collectionHolder = $($(this).data("list-selector"));
        removeCollectionElementForm($collectionHolder);
    });
});

function addCollectionElementForm($collectionHolder) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    if(isNaN(index)){
        index = $collectionHolder.children().length;
    }

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $(newForm);
    $collectionHolder.append($newFormLi);
}

function removeCollectionElementForm($collectionHolder) {
    // get the new index
    var index = $collectionHolder.data('index');

    if(isNaN(index)){
        index = $collectionHolder.children().length;
    }

    if(index <= 0){
        return;
    }


    $collectionHolder.children().last().remove();

    $collectionHolder.data('index', index - 1);
}