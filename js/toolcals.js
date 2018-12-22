
function onConvertObjToFloat(objValue){
    let fValue=0;
    try {
        fValue=parseFloat(objValue);
    }
    catch(err) {
        fValue=0;
    }
    return fValue;
}