import { CurrentUser } from '../decorators/user.decorator';

export const getScopedAgencyId = (
  user: CurrentUser,
  requestedAgencyId?: number,
): number | undefined => {
  if (user.role === 'AGENCY_ADMIN') {
    return user.agency_id ?? undefined;
  }

  return requestedAgencyId;
};
