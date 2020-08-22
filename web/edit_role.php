<?php
namespace MRBS;

use MRBS\Form\ElementFieldset;
use MRBS\Form\ElementInputSubmit;
use MRBS\Form\ElementP;
use MRBS\Form\FieldDiv;
use MRBS\Form\FieldInputText;
use MRBS\Form\Form;

require "defaultincludes.inc";


function generate_add_form($error=null, $name=null)
{
  $form = new Form();
  $form->addHiddenInput('action', 'add')
       ->setAttributes(array('action' => multisite('edit_role_handler.php'),
                             'class'  => 'standard',
                             'method' => 'post'));

  // Name field
  $fieldset = new ElementFieldset();

  if (isset($error))
  {
    $field = new FieldDiv();
    $p = new ElementP();
    $p->setText(get_vocab($error, $name))
      ->setAttribute('class', 'error');
    $field->addControlElement($p);
    $fieldset->addElement($field);
  }

  $field = new FieldInputText();
  // Set a pattern as well as required to prevent a string of whitespace
  $field->setLabel(get_vocab('role_name'))
        ->setControlAttributes(array('name'     => 'name',
                                     'required' => true,
                                     'pattern'  => REGEX_TEXT_POS));
  if (null !== ($maxlength = maxlength('roles.name')))
  {
    $field->setControlAttribute('maxlength', $maxlength);
  }
  $fieldset->addElement($field);
  $form->addElement($fieldset);

  // Submit button
  $fieldset = new ElementFieldset();
  $field = new FieldDiv();
  $element = new ElementInputSubmit();
  $element->setAttribute('value', get_vocab('add_role'));
  $field->addControl($element);
  $fieldset->addElement($field);
  $form->addElement($fieldset);

  $form->render();
}


function generate_roles_table()
{
  $roles = new Roles();

  echo "<table>\n";
  echo "<tbody>\n";

  foreach ($roles as $role)
  {
    echo "<tr>";
    echo "<td>";
    echo $role->name;
    echo "</td>";
    echo "</tr>\n";
  }

  echo "</tbody>\n";
  echo "</table>\n";
}


// Check the user is authorised for this page
checkAuthorised(this_page());

$context = array(
    'view'      => $view,
    'view_all'  => $view_all,
    'year'      => $year,
    'month'     => $month,
    'day'       => $day,
    'area'      => isset($area) ? $area : null,
    'room'      => isset($room) ? $room : null
  );

$error = get_form_var('error', 'string');
$name = get_form_var('name', 'string');

print_header($context);

echo "<h2>" . htmlspecialchars(get_vocab('roles')) . "</h2>";

generate_add_form($error, $name);
generate_roles_table();

print_footer();