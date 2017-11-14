<?php
namespace DennisDigital\Behat\Monetizer101\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Testwork\Hook\HookDispatcher;
use Drupal\DrupalDriverManager;
use Drupal\DrupalExtension\Context\DrupalAwareInterface;

class Monetizer101Context implements DrupalAwareInterface {

  /**
   * @var DrupalDriverManager
   */
  private $drupal;

  /**
   * The environment obtained for the before scenario scope.
   *
   * @var BeforeScenarioScope
   */
  private $environment;

  /**
   * Whether monetizer block should be on or off by default.
   *
   * @var bool
   */
  private $enabledDefaultValue;

  /**
   * Whether a test has changed the block enabled state.
   *
   * @var bool
   */
  private $enabledChanged;

  /**
   * @inheritDoc
   */
  public function setDispatcher(HookDispatcher $dispatcher) {
    // TODO: Implement setDispatcher() method.
  }

  /**
   * @inheritDoc
   */
  public function getDrupal() {
    return $this->drupal;
  }

  /**
   * @inheritDoc
   */
  public function setDrupal(DrupalDriverManager $drupal) {
    $this->drupal = $drupal;
  }

  /**
   * @inheritDoc
   */
  public function setDrupalParameters(array $parameters) {
    // TODO: Implement setDrupalParameters() method.
  }

  /**
   * @BeforeScenario
   *
   * @param BeforeScenarioScope $scope
   */
  public function beforeScenario(BeforeScenarioScope $scope) {
    // Keep the environment available.
    $this->environment = $scope->getEnvironment();
  }

  /**
   * @AfterScenario
   *
   * @param AfterScenarioScope $scope
   */
  public function afterScenario(AfterScenarioScope $scope) {
    // Put the enabled value back to what it was before the scenario ran.
    $this->monetizerBlockIsReset();
  }

  /**
   * @Given Monetizer101 block is enabled
   */
  public function monetizerBlockIsEnabled() {
    // If the value has not changed before, store the original value.;
    if (!$this->enabledChanged) {
      $this->enabledDefaultValue = $this->getVariable('monetizer101_block_enabled');
    }
    // Turn it on.
    $this->enabledChanged = TRUE;
    $this->setVariable('monetizer101_block_enabled', 1);
  }

  /**
   * @Given Monetizer101 block is disabled
   */
  public function monetizerBlockIsDisabled() {
    // If the value has not changed before, store the original value.
    if (!$this->enabledChanged) {
      $this->enabledDefaultValue = $this->getVariable('monetizer101_block_enabled');
    }
    // Turn it off.
    $this->enabledChanged = TRUE;
    $this->setVariable('monetizer101_block_enabled', 0);
  }

  /**
   * @Given Monetizer101 block is reset
   *
   * Reset the block enabled status to the original value.
   */
  public function monetizerBlockIsReset() {
    if ($this->enabledChanged) {
      $val = empty($this->enabledDefaultValue) ? 0 : 1;
      $this->setVariable('monetizer101_block_enabled', $val);
    }
  }

  /**
   * Get a drupal variable.
   */
  protected function getVariable($name) {
    $this->drupal->getDriver('drupal');
    return variable_get($name);
  }

  /**
   * Set a drupal variable.
   */
  protected function setVariable($name, $value) {
    $this->drupal->getDriver('drupal');
    return variable_set($name, $value);
  }
}
