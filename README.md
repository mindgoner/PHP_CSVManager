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
> $CSVManager = new **CSVManager**("**example.csv**", "**;**");

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

## renderContent

To display selected CSV content use **renderContent** method.

 Usage:

> $CSVManager->**renderContent**( **string $renderType** );

**string $renderType** - this parameter is optional. You can type **"pre"** to display result in preformatted text or **"table"** to show result in tabularic form.

Examples:
> $CSVManager->renderContent( **"pre"** ); // Preformatted output
> $CSVManager->renderContent( **"table"** ); // Tabularic form
