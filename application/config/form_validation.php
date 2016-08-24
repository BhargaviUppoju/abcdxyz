<?php

/* setting validation group */

$config = array(
'signup' => array(
        array(
            'field' => 'inputFirstName',
            'label' => 'First Name',
            'rules' => 'required_strict|name|max_length[50]'
        ),
        array(
            'field' => 'inputLastName',
            'label' => 'Last Name',
            'rules' => 'required_strict|name|max_length[50]'
        ),
        array(
            'field' => 'inputUserName',
            'label' => 'User Name',
            'rules' => 'required_strict|min_length[6]|max_length[50]'
        ),
        array(
            'field' => 'inputEmail',
            'label' => 'Email',
            'rules' => 'valid_email'
        ),
        array(
            'field' => 'inputMobileNumber',
            'label' => 'Mobile Number',
            'rules' => 'numeric|min_length[10]|max_length[10]'
        ),
        array(
            'field' => 'inputPassword',
            'label' => 'Password',
            'rules' => 'required_strict|min_length[6]'
        ),
        array(
            'field' => 'inputConfirmPassword',
            'label' => 'Confirm Password',
            'rules' => 'required_strict|min_length[6]'
        )
    )
);

