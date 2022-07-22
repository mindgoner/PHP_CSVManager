# PHP CSVManager

PHP CSVManager is a lightweight manager that allows you to manage your CSV files using PHP!


# Installation

Just copy **CSVManager.php** into your project and use one of the following commands to load manager:
- Using require command:
	> require("CSVManager.php");
- Using require_once command:
	> require_once("CSVManager.php");
- Using include command:
	> include("CSVManager.php");

# Usage

After installing **CSVManager** use following line to initialize manager instance:
> $CSVManager = new **CSVManager**(string "**filename.csv**", string"**delimiter**", boolean **cleanString**, , boolean **associateResults**);

String **filename.csv** specifies which file would you like to use. 
String **delimiter** specifies which character should be used to delimit the file content. Default: ",";
Boolean **cleanString** (optional) specifies whether data should be purgified from unicode to utf-8 leftovers. Defalut: false.
Boolean **associateResults** (optional) if file contains headers, set this flag to true to associate results with names, instead numeric values. Default: false

Optionally, you may define **CSVManager** without defining CSV file name and delimiter:

> $CSVManager = new CSVManager();

In that case, you must use setFilename and setDelimiter methods later:
> $CSVManager->**setFilename**("**example.csv**");
> $CSVManager->**setDelimiter**("**;**");

File name and Delimiter are required options. You might not be able to use manager, without specifing that variables.

## fixPolishCharacters

The **fixPolishCharacters** method allows you to decode polish characters and store them with UTF-8 standard. 

Usage:

> $CSVManager->**fixPolishCharacters**( **boolean $cleanString** );

**boolean $cleanString** parameter specifies whether unicode replacement should be purgified from encoding leftovers or not.

> **Note:** This function should be called immediately after creating the object

## associateResults

If file contains headers use **associateResults** to replace number of columns with key names.

 Usage:

> $CSVManager->**associateResults**();

Example #1:

Before: 
	Array
	(
		[1] => Array
		(
			[0] => 1
			[1] => fruits
			[2] => orange
			[3] => O0001
			[4] => fresh
			[5] => 12
		)

		[2] => Array
		(
			[0] => 2
			[1] => fruits
			[2] => mandarin
			[3] => M0001
			[4] => rotten
			[5] => 11
		)
		[3] => (...)
	)

After:

	Array
	(
		[0] => Array
		(
			[id] => 1
			[category] => fruits
			[product] => orange
			[code] => O0001
			[type] => fresh
			[price] => 12
		)

		[1] => Array
		(
			[id] => 2
			[category] => fruits
			[product] => mandarin
			[code] => M0001
			[type] => rotten
			[price] => 11
		)
	)

## selectColumns

**renderContent** method allows you to select specified columns by name or by id.

 Usage:

> $CSVManager->**renderContent**( **array $columns** );

**array $columns** - Specify which columns used to be selected.

Example #1:
> $CSVManager->renderContent( **["products"]** ); // Select column products

Example #2:
> $CSVManager->renderContent( **["products", "price"]** ); // Select columns products and price


## renderContent

To display selected CSV content use **renderContent** method.

 Usage:

> $CSVManager->**renderContent**( **string $renderType** );

**string $renderType** - this parameter is optional. You can type **"pre"** to display result in preformatted text or **"table"** to show result in tabularic form.

Example #1:
> $CSVManager->renderContent( **"pre"** ); // Preformatted output

Example #2:
> $CSVManager->renderContent( **"table"** ); // Tabularic form
