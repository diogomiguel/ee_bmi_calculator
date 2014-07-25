# BMI Calculator

* Author: [Diogo Silva](https://github.com/diogomiguel)

## Version 0.0.1

Tested only on Expression Engine 2.7+. Shouldn't raise any compatibility problems on any Expression Engine 2+ version, but use with caution.

* Requires: ExpressionEngine 2.7+

## Description

The Body Mass Index (or BMI) Calculator is a way of seeing if your weight is appropriate for your height. The actual calculation is your weight divided by your height squared but it's also easy to read on the chart. BMI can be divided into several categories and generally the higher your BMI, the greater your risk of a large range of medical problems.

BMI charts are calculated for adults only (separate charts are available for children’s weight and heights). Inaccuracies can also occur if you're an athlete or very muscular as this can give you a higher BMI even if you have a healthy level of body fat and this BMI chart is not appropriate for women who are pregnant or breastfeeding, or people who are very frail.

This is just a server-side implementation. Client side validation is not mandatory but recommended.
Please check the sample form HTML page to see the mandatory input fields, their names and what sort of input is expected. 

Supports both imperial and metric units (defaults to metric if values for both are submitted).

## Instalation

Unzip the downloaded zip and place the 'bmi_calculator' folder in /expressionengine/third_party/. In the ExpressionEngine Control Panel navigate to Addons -> Modules and click "Install" next to the "BMI Calculator" addon.

## Form

### Wrap form with

	{exp:bmi_calculator:bmi_form form_id="form-bmi-calculator" return="/bmi-calculator"}

Please note both params are mandatory

### Fields

* Centimeters - `<input type='text' id="bmi-calculator-cm" name="cm" />`
* Feet - `<input type='text' id="bmi-calculator-ft" name="ft" />`
* Inches - `<input type='text' id="bmi-calculator-in" name="in" />`
* Kilograms - `<input type='text' id="bmi-calculator-kg" name="kg" />`
* Stones - `<input type='text' id="bmi-calculator-st" name="st" />`
* Pounds - `<input type='text' id="bmi-calculator-lb" name="lb" />`

To return the bmi value use:

	{exp:bmi_calculator:my_bmi}

Form returns ajax response when submission page is called by AJAX

Format of returned JSON in case of success:

	{
		success: true,
		bmi: <bmi_value>
	} 

Format of returned JSON in case of error:
	
	{
		success: false,
		error: <error message>
	}