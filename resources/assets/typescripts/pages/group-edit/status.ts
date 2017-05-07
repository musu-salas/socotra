import * as cookies from '../../services/cookies';
import { Str } from '../../helpers/Str';
import * as to from '../../helpers/TypesConverter';

export namespace Status {
  interface Result {
    contact: boolean;
    photos: boolean;
    pricing: boolean;
    location: boolean;
    overview: boolean;
    schedule: boolean;
    steps_to_complete: number;
  }

  function getStatus(groupId: number): Promise<Result> {
    return new Promise((resolve, reject) => {
      $.ajax({
        url: `/api/v1/classes/${groupId}/menu`,
        headers: {
          'X-XSRF-TOKEN': cookies.get('XSRF-TOKEN')
        },
        cache: false,
        success: resolve,
        error: () => reject(new Error('Generic error when retrieving group status.'))
      });
    });
  }

  function updateMenu(status: Result) {
    const menu = $('#menu');
    const menuStatus = menu.next('#menu-status');
    const { steps_to_complete: steps } = status;
    const activeItem = menu.find('.item.active');
    const href = activeItem.attr('href');
    const slug = href.substring(href.lastIndexOf('/') + 1) as 'contact' | 'photos' | 'pricing' | 'location' | 'overview' | 'schedule';

    if (status[slug] === true) {
      activeItem.children('i').removeClass('plus red').addClass('checkmark green');

    } else if (status[slug] === false) {
      activeItem.children('i').removeClass('checkmark green').addClass('plus red');
    }

    const templateIndex = (steps > 0) ? 0 : 1;
    const template = menuStatus.children(`span:eq(${templateIndex})`)[0].firstChild.nodeValue;
    const output = (steps > 0) ? Str.sprintf(template, to.string(steps)) : template;

    menuStatus.children('p').html(output).find('strong[data-label-plural]').each(function() {
      let attr = 'data-label';

      if (steps > 1) {
        attr += '-plural';
      }

      this.innerHTML = `${steps} ${this.getAttribute(attr)}`;
    });
  }

  function updateStatusBar(status: Result) {
    const statusBar = $('#status-bar');
    const { steps_to_complete: steps } = status;
    const templateIndex = (steps > 0) ? 0 : 1;
    const template = statusBar.children(`span:eq(${templateIndex})`)[0].firstChild.nodeValue;
    let output = template;

    if (steps > 0) {
      output = Str.sprintf(template, to.string(steps));
      statusBar.addClass('warning').removeClass('success');

    } else {
      statusBar.addClass('success').removeClass('warning');
    }

    statusBar.children('p').html(output).find('strong[data-label-plural]').each(function() {
      let attr = 'data-label';

      if (steps > 1) {
        attr += '-plural';
      }

      this.innerHTML = `${steps} ${this.getAttribute(attr)}`;
    });
  }

  export async function update(groupId: number) {
    const status = await getStatus(groupId);

    updateStatusBar(status);
    updateMenu(status);
  }
}
