/* ------------------------------------------------------------------------
  # jprccron - Joomla cronjobs component for J2.5
  # ------------------------------------------------------------------------
  # author    Prieco S.A.
  # copyright Copyright (C) 2012 Prieco.com. All Rights Reserved.
  # @license - http://http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
  # Websites: http://www.prieco.com
  # Technical Support:  Forum - http://www.prieco.com/en/forum/index.html
  ------------------------------------------------------------------------- */

window.addEvent('domready', function(){

    $each([$('jform_minute2'), $('jform_hour2'), $('jform_day2'), $('jform_month2'), $('jform_weekday2')], function(obj,index){
        obj.addEvent('click',function(e){

            $('jform_unix_mhdmd').value = $('jform_minute2').value + " " + $('jform_hour2').value + " " +
            $('jform_day2').value+" " + $('jform_month2').value+" " + $('jform_weekday2').value;

        });
    });

});


Joomla.submitbutton = function(task)
{
    if (task == '')
    {
        return false;
    }
    else
    {
        var isValid=true;
        var action = task.split('.');
        if (action[1] != 'cancel' && action[1] != 'close')
        {
            var forms = $$('form.form-validate');
            for (var i=0;i<forms.length;i++)
            {
                if (!document.formvalidator.isValid(forms[i]))
                {
                    isValid = false;
                    break;
                }
            }
        }
 
        if (isValid)
        {
            Joomla.submitform(task);
            return true;
        }
        else
        {
            alert(Joomla.JText._('COM_HELLOWORLD_HELLOWORLD_ERROR_UNACCEPTABLE','Some values are unacceptable'));
            return false;
        }
    }
}
