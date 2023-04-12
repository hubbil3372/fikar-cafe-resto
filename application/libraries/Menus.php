<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Menus
{
  protected $ci;
  function __construct()
  {
    $this->ci = &get_instance();
    $this->ci->load->model('Menu_model', 'menu');
  }

  function get_menu_id($slug)
  {
    return $this->ci->menu->get([
      'menuId !=' => '0',
      'menuTautan' => $slug,
    ])->row()->menuId;
  }

  function menu($menus)
  {
    $ref   = [];
    $items = [];
    foreach ($menus as $menu) {
      $thisRef = &$ref[$menu->menuId];

      $thisRef['menuInduk'] = $menu->menuInduk;
      $thisRef['menuLabel'] = $menu->menuLabel;
      $thisRef['menuTautan'] = $menu->menuTautan;
      $thisRef['menuIkon'] = $menu->menuIkon;
      $thisRef['menuId'] = $menu->menuId;

      if ($menu->menuInduk == '0') {
        $items[$menu->menuId] = &$thisRef;
      } else {
        $ref[$menu->menuInduk]['child'][$menu->menuId] = &$thisRef;
      }
    }

    return $items;
  }

  function menu_content()
  {
    $this->ci->load->model('Menu_model', 'menu');
    $menus = $this->ci->menu->get(['menuId !=' => '0'], 'menuUrutan ASC')->result();

    $items = $this->menu($menus);
    return $this->_html_content($items);
  }

  public function _html_content($items, $class = 'dd-list')
  {
    $html = "<ol class=\"" . $class . "\" id=\"menu-id\">";

    foreach ($items as $value) {
      $html .= '<li class="dd-item dd3-item" data-id="' . $value['menuId'] . '" >
                  <div class="dd-handle dd3-handle"></div>
                  <div class="dd3-content"><span id="label_show' . $value['menuId'] . '">' . $value['menuLabel'] . '</span> 
                      <span class="span-right">/<span id="link_show' . $value['menuId'] . '">' . $value['menuTautan'] . '</span> &nbsp;&nbsp; 
                          <a class="edit-button" id="' . $value['menuId'] . '" label="' . $value['menuLabel'] . '" link="' . $value['menuTautan'] . '" icon="' . $value['menuIkon'] . '"><i class="fas fa-edit"></i></a>
                          <a class="del-button" id="' . $value['menuId'] . '"><i class="fas fa-trash"></i></a></span> 
                  </div>';
      if (array_key_exists('child', $value)) {
        $html .= $this->_html_content($value['child'], 'child');
      }
      $html .= "</li>";
    }
    $html .= "</ol>";

    return $html;
  }

  function menu_sidebar()
  {
    $menus = $this->ci->menu->get([
      'menuId !=' => '0',
      'grupMenuGrupId' => $this->ci->ion_auth->get_group_id(),
    ], 'menuUrutan ASC')->result();
    
    $items = $this->menu($menus);
    return $this->_html_sidebar($items);
  }


  public function _html_sidebar($items, $class = null)
  {
    $html = '';
    foreach ($items as $value) {
      $url = site_url($value['menuTautan']);
      $preload = 'waitme';
      $arrow = '';
      if (array_key_exists('child', $value)) {
        $url = 'javascript:;';
        $preload = '';
        $arrow = '<i class="end fas fa-angle-left"></i>';
      }

      $html .= '<li class="nav-item '. $class .'">
                <a href="' . $url . '" class="nav-link ' . $preload . '">
                  <i class="nav-icon ' . $value['menuIkon'] . '"></i>
                  <p>' . "{$value['menuLabel']} {$arrow}" . '</p>
                </a>';
      if (array_key_exists('child', $value)) {
        $html .= '<ul class="nav nav-treeview">';
        $html .= $this->_html_sidebar($value['child'], 'ms-3');
        $html .= '</ul>';
      }
      $html .= "</li>";
    }
    $html .= "</li>";

    return $html;
  }
}
