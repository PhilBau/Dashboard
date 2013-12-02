<?php

class Dashboard_Controller_User extends Zikula_AbstractController
{
    public function view()
    {
        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $uid = $this->request->getSession()->get('uid');

        $helper = new Dashboard_Helper_WidgetHelper($this->entityManager);
        $dashboard = $helper->getUserWidgets($uid);

        $this->view->assign('userWidgets', $dashboard);

        $widgets = $helper->getRegisteredWidgets($uid);

        $this->view->assign('widgets', $widgets);
        $checkbox = (int) $this->request->getSession()->get('dashboard/available_widget_checkbox', false);
        $this->view->assign('available_checkbox', $checkbox);

        return $this->view->fetch('User/view.html.tpl');
    }

    public function addWidget()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $widgetId = $this->request->request->get('id', null);
        if (null === $widgetId) {
            throw new Exception($this->__(sprintf('%s not found', $widgetId)));
	}

        $widget = $this->entityManager->getRepository('Dashboard_Entity_Widget')
            ->findOneBy(array('id' => $widgetId));

        if (!$widget) {
            throw new Exception(sprintf('Widget id %s not found', $widgetId));
        }

        $class = $widget->getClass();
        /* @var Dashboard_AbstractWidget $widget */
        $widget = new $class();
        if (false === ModUtil::available($widget->getModule())) {
            throw new Exception($this->__(sprintf('%s not available (disabled or not installed', $widget->getModule())));
        }

        $uid = $this->request->getSession()->get('uid');

	// If this widget is a default one and the user is admin, add it to all users
	if (isset($_POST['set_default_widget'])) {
	    $defWidget = $this->request->getPost()->get('set_default_widget', null);
	    if (null === $defWidget) {
		throw new Exception($this->__(sprintf('%s not found', $defWidget)));
	    }

	    $widget->setDefWidget(1);
	    Dashboard_Util::addUserWidget($uid, $widget, 1);
	}
	// add it only to the current user
	else {
	    Dashboard_Util::addUserWidget($uid, $widget, 0);
	}

        return $this->redirect(ModUtil::url('Dashboard', 'user', 'view'));
    }

    public function removeWidget()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $id = $this->request->request->get('id', null);
        if (null === $id) {
            throw new Exception($this->__('id not specified'));
        }

        Dashboard_Util::removeUserWidget($id);

        return $this->redirect(ModUtil::url('Dashboard', 'user', 'view'));
    }

    public function updateParameters()
    {
        $this->checkCsrfToken();

        if (!SecurityUtil::checkPermission('Dashboard::', '::', ACCESS_READ)) {
            return LogUtil::registerPermissionError();
        }

        $id = $this->request->request->get('id', null);
        if (null === $id) {
            throw new Exception($this->__('id not specified'));
        }

        $index = 1;
        $serialized = '{';

        // Serialize the widget parameters
        while(true) {
            $paramName = $this->request->request->get('param'.'name'.$index, null);
            if (null === $paramName) {
                break;
            }

            // Get the parameters
	    $param = $this->request->getPost()->get('param'.$index, null);
            if (null === $param) {
	        throw new Exception($this->__('param not specified'));
            }

	    // the parameters can also be an array
            if (is_array($param)) {
                $param =  implode("|",$param);
            } 

            if ($index != 1) {
                $serialized .= ', "';	
            } else {
                $serialized .= '"';
            }
	    
            $serialized .= $paramName.'":"'.$param.'"';

            ++$index;
        }

        $serialized .= '}';

        $uid = $this->request->getSession()->get('uid');
        Dashboard_Util::updateUserParameters($id, $serialized);

        return $this->redirect(ModUtil::url('Dashboard', 'user', 'view'));
    }
}
