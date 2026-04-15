*** Settings ***
Library    SeleniumLibrary
Library    Collections
Suite Setup    Log    Lancement des smoke tests LegaliaBot
Suite Teardown    Close All Browsers

*** Variables ***
${BASE_URL}          http://127.0.0.1:8088
${BROWSER}           Chrome
${WINDOW_WIDTH}      1440
${WINDOW_HEIGHT}     1100
${MOBILE_WIDTH}      390
${MOBILE_HEIGHT}     844

*** Test Cases ***
Landing Page Loads And Navigates To Authentication Screens
    Open Test Browser
    Go To    ${BASE_URL}/acceuil/index.php
    Wait Until Page Contains    Bienvenue sur LegaliaBot
    Click Link    Connexion
    Wait Until Location Contains    /auth/authentification.php
    Go To    ${BASE_URL}/acceuil/index.php
    Click Link    Créer un compte
    Wait Until Location Contains    /auth/connexion.php

Registration Page Validates Required Fields And Password Confirmation
    Open Test Browser
    Go To    ${BASE_URL}/auth/connexion.php
    Click Button    css:.signup-btn
    Wait Until Page Contains    Veuillez remplir tous les champs.
    Input Text    id=email    test@example.com
    Input Password    id=password    motdepasse123
    Input Password    id=confirm    autre123
    Click Button    css:.signup-btn
    Wait Until Page Contains    Les mots de passe ne correspondent pas.

Authentication Page Validates Required Fields
    Open Test Browser
    Go To    ${BASE_URL}/auth/authentification.php
    Click Button    css:.signin-btn
    Wait Until Page Contains    Veuillez remplir tous les champs.

Faq Accordion Opens Answer
    Open Test Browser
    Go To    ${BASE_URL}/acceuil/faqetassistance.php
    Click Element    xpath=//span[contains(., "Quels sont mes droits en cas de licenciement abusif ?")]
    Wait Until Element Is Visible    css:.faq-item.active .faq-answer

Landing Page Uses Readable French Labels
    Open Test Browser
    Go To    ${BASE_URL}/acceuil/index.php
    Page Should Contain Link    Créer un compte
    Page Should Contain    Réponses instantanées

Chat Page Loads Without Backend Fatal Error
    Open Test Browser
    Go To    ${BASE_URL}/chat_bot/pas.php
    Page Should Not Contain    Erreur DB :
    Page Should Contain    Connexion requise

Account Page Loads Without Backend Fatal Error
    Open Test Browser
    Go To    ${BASE_URL}/information_personnel/compte.php
    Page Should Not Contain    Erreur DB :
    Page Should Contain    Connexion requise

Contact Page Loads And Shows Form
    Open Test Browser
    Go To    ${BASE_URL}/information_personnel/contact.php
    Wait Until Page Contains    Contactez-nous
    Page Should Contain Element    id=message

Contact Form Submits Successfully
    Open Test Browser
    Go To    ${BASE_URL}/information_personnel/contact.php
    Input Text    id=nom    Test Robot
    Input Text    id=email    robot@example.com
    Input Text    id=sujet    Verification
    Input Text    id=message    Ceci est un message de test envoye par Robot Framework.
    Click Button    css:button[type="submit"]
    Wait Until Page Contains    Votre message a bien ete enregistre.

Accueil Navigation Opens On Mobile
    Open Mobile Browser
    Go To    ${BASE_URL}/acceuil/accueil.php
    Element Should Be Visible    css:.hamburger
    Click Element    css:.hamburger
    Wait Until Element Is Visible    css:.nav-menu.active

Sidebar Opens On Mobile Chat Page
    Open Mobile Browser
    Go To    ${BASE_URL}/chat_bot/pas.php
    Element Should Be Visible    css:.sidebar-toggle
    Click Button    css:.sidebar-toggle
    Wait Until Element Is Visible    css:.sidebar.is-open

*** Keywords ***
Open Test Browser
    ${chrome_options}=    Evaluate    selenium.webdriver.ChromeOptions()    modules=selenium.webdriver
    ${headless}=    Set Variable    --headless=new
    ${window_size}=    Set Variable    --window-size=${WINDOW_WIDTH},${WINDOW_HEIGHT}
    ${disable_gpu}=    Set Variable    --disable-gpu
    ${no_sandbox}=    Set Variable    --no-sandbox
    Call Method    ${chrome_options}    add_argument    ${headless}
    Call Method    ${chrome_options}    add_argument    ${window_size}
    Call Method    ${chrome_options}    add_argument    ${disable_gpu}
    Call Method    ${chrome_options}    add_argument    ${no_sandbox}
    Create Webdriver    ${BROWSER}    options=${chrome_options}
    Set Selenium Timeout    10 seconds
    Set Selenium Implicit Wait    0.5 seconds

Open Mobile Browser
    Open Test Browser
    Set Window Size    ${MOBILE_WIDTH}    ${MOBILE_HEIGHT}
