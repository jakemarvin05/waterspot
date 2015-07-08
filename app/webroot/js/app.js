function sameHeight(firstClass,secondClass){

    var leftSideHeight = $('.'+[firstClass]).height();
    var rightSideHeight = $('.'+[secondClass]).height();
    var commonHeight = 400;

    if(leftSideHeight>rightSideHeight){
        $('.'+[secondClass]).height(leftSideHeight+42);
    }
    else{
        $('.'+[firstClass]).height(rightSideHeight-42);
    }


}