function ImageLoaded(image) 
{
    var wrapper = image.parentElement;
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");

    if(image.offsetWidth > wrapper.offsetWidth)
    {
        image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
    }
    else
    {
        image.style.left = 0;
        image.style.width = wrapper.offsetWidth + "px";
    }

    if(image.offsetHeight > wrapper.offsetHeight)
    {
        image.style.top = "-" + (image.height - wrapper.offsetWidth) / 2 + "px";
    }
}

function DisplayImage(image)
{
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");
}

function PreviewSelectorLoaded(image) 
{
    var wrapper = image.parentElement;
    $(image).removeClass("visibility-hidden");
    $(image).addClass("animated fadeIn");
}

function CenterPreviewSelectors()
{

    $(".preview-selector-image").each(function(index)
    {
        var image = $(".preview-selector-image")[index];
        var wrapper = image.parentElement;

        image.style.height = wrapper.offsetHeight + "px";
        if(image.offsetWidth > wrapper.offsetWidth)
        {
            image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
        }
        else
        {
            image.style.left = 0;
        }
    });
}

function OnResizeGallery()
{
    $(".art-image").each(function(index)
    {
        var wrapper = $(".art-image")[index].parentElement;
        var image = $(".art-image")[index];

        if(image.offsetWidth > wrapper.offsetWidth)
        {
            image.style.left = "-" + (image.offsetWidth - wrapper.offsetWidth)/2 + "px";
        }
        else
        {
            image.style.left = 0;
            image.style.width = wrapper.offsetWidth + "px";
        }

        if(image.offsetHeight > wrapper.offsetHeight)
        {
            image.style.top = "-" + (image.height - wrapper.offsetWidth) / 2 + "px";
        }
    });
}

function ArtworkPreviewLoaded(image)
{
    $(image).removeClass("visibility-hidden animated fadeIn");
    $(image).addClass("animated fadeIn");
    var wrapper = image.parentElement;
    $(wrapper).height($(image).height());
}

function SelectPreview(pictureName, element)
{
    $("#artwork-image").attr("src", "/storage/artworks/" + pictureName);
    $(".artwork-selector").addClass("artwork-selector-inactive");
    $(element).removeClass("artwork-selector-inactive");

    $('html, body').animate({
        scrollTop: $("#artwork-image").offset().top - 20
    }, 500);
}

function SelectArtworkSize(size, element, alias)
{
    $("#selected-artwork-size").html(size);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
    $("#artwork-size").val(alias);
    GetArtworkPrice();
}

function SelectPaperMaterial(material, element)
{
    $("#selected-material").html(material);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
}

function SelectFrame(frame, element)
{
    $("#frame").html(frame);
    $(element).parent().find(".customization-select-option").removeClass("artwork-selected-option");
    $(element).addClass("artwork-selected-option");
    $("#frame").val(frame);
}

function QuantityChanged()
{

    $("#quantity-error").html("");
    if(!$.isNumeric($("#quantity-input").val()))
    {
        $("#quantity-error").html("Please enter a number");
        return;
    }

    var quantity = $("#quantity-input").val();
    $("#quantity").html(quantity);
    $("#count").val(quantity);
    GetArtworkPrice();
}


/// HTTP CALLS ///
function GetArtworkPrice()
{
    $.ajax({
        type: "POST",
        url: "/artwork/getprice",
        data: {_token: $('#_token').val(), size: $("#artwork-size").val(), id: $("#artwork-id").val(), quantity: $("#quantity-input").val()},
        success: function(data)
        {
            $("#total-price").html(data);
        },
        error: function(data)
        {
            $("#total-price").html("Something went wrong, try again.");
        }
    });
}
////////////////////





OnResizeGallery();
CenterPreviewSelectors();

$(window).resize(function () 
{
    $(window).trigger("window:resize")
});

$(window).on("window:resize", function (e) 
{
    OnResizeGallery();
    CenterPreviewSelectors();
});