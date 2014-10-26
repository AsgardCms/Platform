<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">
            <?php $items = \Modules\Core\Navigation\NavigationOrdener::order($items); ?>
            <?php foreach($items as $i => $item): ?>
                <?php if (is_object($item)): ?>
                    <?php if ($item[0]['permission']): ?>
                        <li class="treeview {{ $item[0]['request'] ? 'active' : ''}}">
                            <a href="#">
                                <i class="{{ $item[0]['icon-class'] }}"></i> <span>{{ $item[0]['title'] }}</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <?php $item->shift(); ?>
                            <ul class="treeview-menu">
                                <?php foreach($item as $subItem): ?>
                                    <?php if ($subItem['permission']): ?>
                                        <li class="{{ Request::is($subItem['request']) ? 'active' : ''}}">
                                            <a href="{{ URL::route($subItem['route']) }}"><i class="{{$subItem['icon-class']}}"></i> {{ $subItem['title'] }}</a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($item['permission']): ?>
                        <li class="{{ Request::is($item['request']) ? 'active' : ''}}">
                            <a href="{{ URL::route($item['route']) }}">
                                <i class="{{ $item['icon-class'] }}"></i> <span>{{ $item['title'] }}</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
