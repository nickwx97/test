pipeline {
  agent any
  stages {
	stage('Checkout SCM') {
	  steps {
		checkout(
			[$class: 'GitSCM', branches: [[name: '*/main']],
			doGenerateSubmoduleConfigurations: false,
			extensions: [],
			submoduleCfg: [],
			userRemoteConfigs: [[url: 'https://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@github.com/SIT-ICT3x03/Team10-AY21.git']]]
		)
	  }	  
	}

	stage('Build') {
	  steps {
		echo 'build'
			sh 'composer install'
			sh 'composer update'
	  }
	}

	stage('OWASP DependencyCheck') {
	  steps {
		dependencyCheck additionalArguments: '--format HTML --format XML --suppression suppression.xml', odcInstallation: 'Default'
	  }
	}

	stage('Test') {
	  parallel {
		stage('PHPLint') {
		  steps {
			echo 'validating....'
				  sh 'find app -name "*.php" -print0 | xargs -0 -n1 php -l'
		  }
		}
		
		stage('PHPUnit') {
		  steps {
			echo 'testing....'
			//unit testing  
			sh './vendor/bin/phpunit --log-junit logs/unitreport.xml -c tests/phpunit.xml tests' 
		  }
		}
	  }
	}
	
	  stage('Integration UI Test') {
	  parallel {
		stage('Selenium Server') {
		  steps {
			echo 'deploying....'
			//deploying selenium server
			sh './selenium.sh &'
				}
			}
		stage('Headless Browser Test') {
		  steps {
			echo 'testing....'
			//UI testing  
			sh './vendor/bin/phpunit --log-junit logs/uiTestreport.xml -c tests/phpunit.xml uiTests' 
		  } 
		}
	  }
	}
  }
  post {		
		always {
		  junit testResults: 'logs/unitreport.xml'
		  junit testResults: 'logs/uiTestreport.xml'
		}
	  success {
			dependencyCheckPublisher pattern: 'dependency-check-report.xml'
		}
	}
}  


